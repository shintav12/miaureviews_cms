<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use App\Models\EditorImages;
use App\Utils\imageUploader;

class EditorImagesController extends BaseController {

    public function search(){
        $images = EditorImages::orderBy("created_at","des")->get(["id","path","text"]);
        foreach ($images as $key => $value) {
        	$value->path = config("app.path_url").$value->path;
        }
        return response(json_encode(array("images"=>$images)));
    }

    public function save(Request $request){
        try{
            $model = new EditorImages();
            $model->text = '';
            $model->path = '';
            $model->save();
            $image = $request->file('gallery_file');
            $path = imageUploader::upload($model,$image,"gallery_editor");
            $model->path = $path;
            $model->save();
            $path = config("app.path_url").$model->path;
            return response(json_encode(array("error" => 0,"path"=>$path)), 200);
        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

   
}
