<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Utils;
use App\Models\Meta;

/**
 * Description of Metas
 *
 * @author Luis
 */
class Metas {
    
    public static function get($type_object,$object){
        return Meta::where(array("object_id"=>$object,"type"=>$type_object))->first();
    }
    
    public static function save($input,$file,$type_object,$object){
        $meta = Meta::where(array("object_id"=>$object,"type"=>$type_object))->first();
        if(!$meta){
            $meta = new Meta();
        }
        $meta->object_id = $object;
        $meta->type = $type_object;
        $meta->meta_index = isset($input["meta_index"]) ? 1 : 0;
        $meta->meta_follow = isset($input["meta_follow"]) ? 1 : 0;
        $meta->fb_title = $input["fb_title"];
        $meta->fb_description = $input["fb_description"];
        $meta->tw_title = $input["tw_title"];
        $meta->tw_description = $input["tw_description"];
        $meta->meta_keywords = $input["keywords"];
        $meta->meta_title = $input["meta_title"];
        $meta->meta_description = $input["meta_description"];
        $image = $file;
        $meta->save();
        if(!is_null($image)){
            $path = imageUploader::upload($meta,$image,"meta");
            $meta->path = $path;
            $meta->save();
        }   
    }
}

