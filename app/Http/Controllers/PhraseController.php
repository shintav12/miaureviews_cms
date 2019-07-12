<?php

namespace App\Http\Controllers;

use App\Models\Phrase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use Yajra\DataTables\DataTables;
use Illuminate\Routing\Controller as BaseController;

class PhraseController extends BaseController
{
    public function index(){
        $template = array(
            "menu_active" => "phrases",
            "smenu_active" => "",
            "page_title" => "Frases",
            "page_subtitle" => "",
            "user" => session('user')
        );
        return view('phrase.index',$template);
    }

    public function load(){
        $phrases = DB::select(DB::raw("select m.id, m.title, m.created_at, m.updated_at 
                                      from phrases m
                                      order by m.id ASC"));
        return DataTables::of($phrases)
            ->make(true);
    }

    public function detail($id = 0){
        $template = array(
            "menu_active" => "phrases",
            "smenu_active" => "",
            "page_title" => "Frases",
            "page_subtitle" => ($id == 0 ? "Nuevo" : "Editar" ),
            "user" => session('user')
        );

        if($id != 0){
            $user = Phrase::find($id);
            $template['item'] = $user;
        }

        return view('phrase.detail',$template);
    }

    public function save(Request $request){
        try{
            $id = Input::get('id');
            $title = Input::get('title');
            $text = Input::get('text');


            if($id != 0) {
                $phrase = Phrase::find($id);
                $phrase->updated_at = date('Y-m-d H:i:s');
            }
            else{
                $phrase  = new Phrase();
                $phrase->created_at = date('Y-m-d H:i:s');
            }
            $phrase->title = $title;
            $phrase->text = $text;
            $phrase->save();

            return response(json_encode(array("error" => 0,"id" => $phrase->id)), 200);

        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }
}
