<?php

Route::any('/', ["as" => "admin_index", "uses" => "LoginController@login"]);
Route::any('/logout', ["as" => "admin_logout", "uses" => 'LoginController@logout']);

Route::group(['prefix' => 'auth_users'], function (){
    Route::get('/','AuthUserController@index')->middleware('verify_permissions')->name('auth_users');
    Route::get('/get_types','AuthUserController@load')->name('get_user');
    Route::post('change_status','AuthUserController@change_status')->name('change_status_user');
    Route::post('/save','AuthUserController@save')->name('user_save');
    Route::get('/detail/{id?}','AuthUserController@detail')->middleware('verify_permissions')->name('detail_user');
});

Route::group(['prefix' => 'auth_role'], function (){
    Route::get('/','AuthRoleController@index')->middleware('verify_permissions')->name('auth_role');
    Route::get('/get_types','AuthRoleController@load')->name('get_role');
    Route::post('change_status','AuthRoleController@change_status')->name('change_status_role');
    Route::post('/save','AuthRoleController@save')->name('role_save');
    Route::post('/perms_save','AuthRoleController@permissionsSave')->name('perms_save');
    Route::get('/detail/{id?}','AuthRoleController@detail')->middleware('verify_permissions')->name('role_user');
    Route::get('/perms/{id}','AuthRoleController@perms')->middleware('verify_permissions')->name('perms');
});

Route::group(['prefix' => 'slider'], function (){
    Route::get('/','SliderController@index')->middleware('verify_permissions')->name('slider');
    Route::get('/get_types','SliderController@load')->name('get_slider');
    Route::post('change_status','SliderController@change_status')->name('change_status_slider');
    Route::post('/save','SliderController@save')->name('slider_save');
    Route::get('/detail/{id?}','SliderController@detail')->middleware('verify_permissions')->name('slider_detail');
});


Route::group(['prefix' => 'posts'], function (){
    Route::get('/search','PostController@search')->middleware('verify_permissions')->name('post_search');
    Route::get('/search_tag','PostController@post_tag')->middleware('verify_permissions')->name('post_tag');
    Route::get('/','PostController@index')->middleware('verify_permissions')->name('posts');
    Route::get('/get_types','PostController@load')->name('get_post');
    Route::get('/detail/{id?}','PostController@detail')->middleware('verify_permissions')->name('post_detail');
    Route::post('/save','PostController@save')->name('post_save');
    Route::post('change_status','PostController@change_status')->name('change_status_post');
    Route::post('/save_tags','PostController@save_tags')->name('save_tags');
});

Route::group(['prefix' => 'videos'], function (){
    Route::get('/search','VideoController@search')->middleware('verify_permissions')->name('video_search');
    Route::get('/search_tag','VideoController@post_tag')->middleware('verify_permissions')->name('video_tag');
    Route::get('/','VideoController@index')->middleware('verify_permissions')->name('videos');
    Route::get('/get_types','VideoController@load')->name('get_videos');
    Route::get('/detail/{id?}','VideoController@detail')->middleware('verify_permissions')->name('video_detail');
    Route::post('/save','VideoController@save')->name('video_save');
    Route::post('change_status','VideoController@change_status')->name('change_status_video');
    Route::post('/save_tags','VideoController@save_tags')->name('save_video_tags');
});

Route::group(['prefix' => 'editor_images'], function (){
    Route::post('upload','EditorImagesController@save')->name('upload_images');
    Route::get('search','EditorImagesController@search')->name('search_images');
});

Route::group(['prefix' => 'frebies'], function (){
    Route::get('/search','FrebieController@search')->middleware('verify_permissions')->name('frebie_search');
    Route::get('/search_tag','FrebieController@post_tag')->middleware('verify_permissions')->name('post_tag');
    Route::get('/','FrebieController@index')->middleware('verify_permissions')->name('frebies');
    Route::get('/get_types','FrebieController@load')->name('get_frebies');
    Route::get('/detail/{id?}','FrebieController@detail')->middleware('verify_permissions')->name('frebie_detail');
    Route::post('/save','FrebieController@save')->name('frebie_save');
    Route::post('change_status','FrebieController@change_status')->name('change_status_frebie');
    Route::post('/save_tags','FrebieController@save_tags')->name('save_tags');
});

Route::group(['prefix' => 'categories'], function (){
    Route::get('/search','CategoryController@search')->middleware('verify_permissions')->name('categories_search');
    Route::get('/search_tag','CategoryController@post_tag')->middleware('verify_permissions')->name('post_tag');
    Route::get('/','CategoryController@index')->middleware('verify_permissions')->name('categories');
    Route::get('/get_types','CategoryController@load')->name('get_categories');
    Route::get('/detail/{id?}','CategoryController@detail')->middleware('verify_permissions')->name('category_detail');
    Route::post('/save','CategoryController@save')->name('category_save');
    Route::post('change_status','CategoryController@change_status')->name('change_status_category');
    Route::post('/save_tags','CategoryController@save_tags')->name('save_tags');
});

Route::group(['prefix' => 'products'], function (){
    Route::get('/search','ProductController@search')->middleware('verify_permissions')->name('products_search');
    Route::get('/search_tag','ProductController@post_tag')->middleware('verify_permissions')->name('post_tag');
    Route::get('/','ProductController@index')->middleware('verify_permissions')->name('products');
    Route::get('/get_types','ProductController@load')->name('get_products');
    Route::get('/detail/{id?}','ProductController@detail')->middleware('verify_permissions')->name('product_detail');
    Route::post('/save','ProductController@save')->name('product_save');
    Route::post('change_status','ProductController@change_status')->name('change_status_product');
    Route::post('/save_tags','ProductController@save_tags')->name('save_tags');
});

Route::group(['prefix' => 'phrases'], function (){
    Route::get('/','PhraseController@index')->middleware('verify_permissions')->name('phrases');
    Route::get('/get_types','PhraseController@load')->name('get_phrases');
    Route::post('/save','PhraseController@save')->name('phrase_save');
    Route::get('/detail/{id?}','PhraseController@detail')->middleware('verify_permissions')->name('phrase_detail');
});

Route::group(['prefix' => 'social_media'], function (){
    Route::get('/','SocialMediaController@index')->middleware('verify_permissions')->name('social_media');
    Route::get('/get_types','SocialMediaController@load')->name('get_social_media');
    Route::post('change_status','SocialMediaController@change_status')->name('change_status_social_media');
    Route::post('/save','SocialMediaController@save')->name('social_media_save');
    Route::get('/detail/{id?}','SocialMediaController@detail')->middleware('verify_permissions')->name('social_media_detail');
});
