<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\ImageType;
use App\Models\Frebie;
use App\Models\ObjectTag;
use App\Models\Tag;
use App\Utils\imageUploader;
use App\Utils\Metas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use Yajra\DataTables\DataTables;
use Illuminate\Routing\Controller as BaseController;

class FrebieController extends BaseController
{
    public function index(){
        $template = array(
            "menu_active" => "frebies",
            "smenu_active" => "",
            "page_title" => "Frebie",
            "page_subtitle" => "",
            "user" => session('user')
        );
        return view('frebie.index',$template);
    }

    public function post_tag(Request $request){
        $post = $request["title"];
        $posts = Tag::where('name','LIKE',"%{$post}%")->limit(10)->get();
        return Response()->json(['data'=>$posts]);
    }

    public function save_tags(Request $request){
        try{
            $tags = trim(Input::get('new_tag'));
            $tags = explode(',',$tags);
            foreach($tags as $tag){
                $tag = trim($tag);
                $coincidence = DB::select('select id from tags where lower(name) = "'.strtolower($tag).'"');
                if(count($coincidence) < 1){
                    $new_tag = new Tag();
                    $new_tag->name = $tag;
                    $new_tag->slug = $new_tag->get_slug($tag,$new_tag->getTable());
                    $new_tag->save();
                }
            }
            return response(json_encode(array("error" => 0)), 200);
        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

    public function load(){
        $frebies = DB::select(DB::raw("select m.id, m.title, m.created_at, m.updated_at ,m.status 
                                  from frebies m
                                  order by m.id ASC"));
        return DataTables::of($frebies)
            ->make(true);
    }

    public function change_status(){
        try{
            $id = Input::get('id');
            $status = intval(Input::get('status'));
            $frebie = Frebie::find($id);
            $frebie->status = $status;
            $frebie->save();

            return response(json_encode(array("error" => 0)), 200);
        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

    public function detail($id = 0){
        $template = array(
            "menu_active" => "frebies",
            "smenu_active" => "",
            "page_title" => "Freebies",
            "page_subtitle" => ($id == 0 ? "Nuevo" : "Editar" ),
            "user" => session('user'),
            "image_types" => ImageType::get()
        );

        if($id != 0){
            $frebie = Frebie::find($id);
            $images = Image::where('object_id',$frebie->id)->where("object_type","frebie")->get();
            $frebie->content = json_decode($frebie->content);
            $tags = DB::select("select t.id, t.name from tags t join object_tag pt on pt.tag_id = t.id where pt.object_type = 'frebie'  and pt.object_id = ".$id);
            $template['item'] = $frebie;
            $template["meta"] = Metas::get("frebie", $frebie->id);
            $template["tags"] = $tags;
            $template["images"] = $images;
        }

        return view('frebie.detail',$template);
    }

    public function save(Request $request){
        try{
            $id = Input::get('id');
            $image_types = ImageType::get();
            $description = Input::get('description');
            $description = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $description);
            $matches = [];
            preg_match_all("/<\s*p[^>]*>([^<]*)<\s*\/\s*p\s*>/", $description,$matches);

            if(count($matches[0]) == 0){
                $description = "<p>".$description."</p>";
            }
            $title = Input::get('title');
            $subtitle = Input::get('subtitle');
            $image = $request->file('file_post');
            $download = $request->file('frebie_file');
            $tags = Input::get('tag_id');

            if($id != 0) {
                $frebie = Frebie::find($id);
            }
            else{
                $frebie  = new Frebie();
                $frebie->status = 0;
                $frebie->slug = $frebie->get_slug($title,$frebie->getTable());
            }
            $frebie->title = $title;
            $frebie->subtitle = $subtitle;
            $description = str_replace('"', "'", $description);
            $frebie->content = json_encode($description);
            $path = sprintf("http://www.karellyscosta.com/freebies/%s",$frebie->slug);
            $data = file_get_contents('http://tinyurl.com/api-create.php?url='.$path);
            $frebie->tiny_url = $data;
            $frebie->save();
            if(!is_null($image)){
                $path = imageUploader::upload($frebie,$image,"frebie");
                $frebie->image = $path;
                $frebie->save();
            }

            if(!is_null($download)){
                $path = imageUploader::uploadFile($frebie,$download,"downloads");
                $frebie->download = $path;
                $frebie->save();
            }

            ObjectTag::where('object_id',$frebie->id)->where("object_type","frebie")->delete();
            if(is_array($tags)){
                foreach($tags as $tag){
                    $post_tag = new ObjectTag();
                    $post_tag->object_id = $frebie->id;
                    $post_tag->object_type = "frebie";
                    $post_tag->tag_id = intval($tag);
                    $post_tag->save();
                }
            }

            foreach ($image_types as $image_type){
                $image = $request->file($image_type->name);
                if(!is_null($image)){
                    $post_image_type = Image::where("object_id",$frebie->id)->where("object_type","frebie")
                        ->where("image_type",$image_type->id)->first();
                    if(is_null($post_image_type)){
                        $post_image_type = new Image();
                        $post_image_type->object_id = $frebie->id;
                        $post_image_type->object_type = "frebie";
                        $post_image_type->image_type = $image_type->id;
                        $post_image_type->save();
                    }
                    $path = imageUploader::upload($post_image_type,$image,"images");
                    $post_image_type->image = $path;
                    $post_image_type->save();
                }
            }

            Metas::save(Input::all(), $request->file('file'), "frebie", $frebie->id);
            return response(json_encode(array("error" => 0,"id" => $frebie->id)), 200);

        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }
}
