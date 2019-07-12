<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\ImageType;
use App\Models\Video;
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

class VideoController extends BaseController
{
    public function index(){
        $template = array(
            "menu_active" => "videos",
            "smenu_active" => "",
            "page_title" => "Video",
            "page_subtitle" => "",
            "user" => session('user')
        );
        return view('video.index',$template);
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
        $videos = DB::select(DB::raw("select m.id, m.title, m.created_at, m.updated_at ,m.status 
                                  from videos m
                                  order by m.id ASC"));
        return DataTables::of($videos)
            ->make(true);
    }

    public function change_status(){
        try{
            $id = Input::get('id');
            $status = intval(Input::get('status'));
            $video = Post::find($id);
            $video->status = $status;
            $video->save();

            return response(json_encode(array("error" => 0)), 200);
        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

    public function detail($id = 0){
        $template = array(
            "menu_active" => "videos",
            "smenu_active" => "",
            "page_title" => "Video",
            "page_subtitle" => ($id == 0 ? "Nuevo" : "Editar" ),
            "user" => session('user'),
            "image_types" => ImageType::get()
        );

        if($id != 0){
            $video = Video::find($id);
            $images = Image::where('object_id',$video->id)->where("object_type","video")->get();
            $video->text = json_decode($video->text);
            $video->video = json_decode($video->video);
            $tags = DB::select("select t.id, t.name from tags t join object_tag pt on pt.tag_id = t.id where pt.object_type = 'video'  and pt.object_id = ".$id);
            $template['item'] = $video;
            $template["meta"] = Metas::get("video", $video->id);
            $template["tags"] = $tags;
            $template["images"] = $images;
        }

        return view('video.detail',$template);
    }

    public function save(Request $request){
        try{
            $id = Input::get('id');
            $video_code = Input::get('video');
            $image_types = ImageType::get();
            $description = Input::get('description');
            $subtitle = Input::get('subtitle');
            $description = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $description);
            $matches = [];
            preg_match_all("/<\s*p[^>]*>([^<]*)<\s*\/\s*p\s*>/", $description,$matches);

            if(count($matches[0]) == 0){
                $description = "<p>".$description."</p>";
            }
            $title = Input::get('title');
            $image = $request->file('file_post');
            $tags = Input::get('tag_id');

            if($id != 0) {
                $video = Video::find($id);
            }
            else{
                $video  = new Video();
                $video->status = 0;
                $video->slug = $video->get_slug($title,$video->getTable());
            }
            $video->title = $title;
            $video->subtitle = $subtitle;
            $description = str_replace('"', "'", $description);
            $video->text = json_encode($description);
            $video->video = json_encode($video_code);
            $path = sprintf("http://www.karellyscosta.com/posts/%s",$video->slug);
            $data = file_get_contents('http://tinyurl.com/api-create.php?url='.$path);
            $video->tiny_url = $data;
            $video->save();
            if(!is_null($image)){
                $path = imageUploader::upload($video,$image,"video");
                $video->image = $path;
                $video->save();
            }

            ObjectTag::where('object_id',$video->id)->where("object_type","video")->delete();
            if(is_array($tags)){
                foreach($tags as $tag){
                    $video_tag = new ObjectTag();
                    $video_tag->object_id = $video->id;
                    $video_tag->object_type = "video";
                    $video_tag->tag_id = intval($tag);
                    $video_tag->save();
                }
            }

            foreach ($image_types as $image_type){
                $image = $request->file($image_type->name);
                if(!is_null($image)){
                    $video_image_type = Image::where("object_id",$video->id)->where("object_type","video")
                        ->where("image_type",$image_type->id)->first();
                    if(is_null($video_image_type)){
                        $video_image_type = new Image();
                        $video_image_type->object_id = $video->id;
                        $video_image_type->object_type = "video";
                        $video_image_type->image_type = $image_type->id;
                        $video_image_type->save();
                    }
                    $path = imageUploader::upload($video_image_type,$image,"images");
                    $video_image_type->image = $path;
                    $video_image_type->save();
                }
            }

            Metas::save(Input::all(), $request->file('file'), "video", $video->id);
            return response(json_encode(array("error" => 0,"id" => $video->id)), 200);

        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }
}
