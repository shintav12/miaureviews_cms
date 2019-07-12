<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Description of ContentType
 *
 * @author luis
 */
class AuthUser extends \Illuminate\Database\Eloquent\Model {

    protected $table = "auth_user";
    public $timestamps = false;

    public function social_network(){
        return $this->belongsToMany('App\Models\SocialNetwork','auth_user_social');
    }

}
