<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;
use DB;
/**
 * Description of BaseModel
 *
 * @author luis
 */
class BaseModel extends \Illuminate\Database\Eloquent\Model{
    //put your code here

    public static function slugify($title){
        $title=strtr(utf8_decode($title), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        $slug = trim($title);
        $slug = preg_replace('/[^a-zA-Z0-9 -]/', '', $slug); // only take alphanumerical characters, but keep the spaces and dashes too...
        $slug = str_replace(' ', '-', $slug); // replace spaces by dashes
        $slug = strtolower($slug);  // make it lowercase
        return $slug;
    }

    public static function get_slug($field, $table){
        $slug = self::slugify($field);
        $query = "select id from `" . $table . "` where slug REGEXP '^{$slug}(-[0-9]*)?$'";
        $slugCount = count(DB::select($query));
        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }

    public static function get_slug_id($field,$table,$id){
        $slug = self::slugify($field);
        $query = "select id from `" . $table . "` where id !=$id and slug REGEXP '^{$slug}(-[0-9]*)?$'";
        $slugCount = count(DB::select($query));
        return ($slugCount > 0) ? "{$field}-{$slugCount}" : $slug;
    }
}
