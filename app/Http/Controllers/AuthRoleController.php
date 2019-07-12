<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use Yajra\Datatables\Datatables;
use App\Models\AuthRole;
use App\Models\AuthRoleObject;

class AuthRoleController extends BaseController {

    public function index(){
        $template = array(
            "menu_active" => "auth_role",
            "smenu_active" => "",
            "page_title" => "Roles",
            "page_subtitle" => "",
            "user" => session('user')
        );
        return view('auth_role.index',$template);
    }

    public function change_status(){
        try{
            $id = Input::get('id');
            $status = intval(Input::get('status'));
            $menu = AuthRole::find($id);
            $menu->status = $status;
            $menu->save();

            return response(json_encode(array("error" => 0)), 200);
        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

    public function load(){
        $users = DB::select(DB::raw("select m.id, m.name, m.created_at, m.updated_at, m.status 
                                      from auth_role m
                                      order by m.id ASC"));
        return Datatables::of($users)
            ->make(true);
    }

    public function detail($id = 0){
        $template = array(
            "menu_active" => "auth_role",
            "smenu_active" => "",
            "page_title" => "Roles",
            "page_subtitle" => ($id == 0 ? "Nuevo" : "Editar" ),
            "user" => session('user')
        );

        if($id != 0){
            $user = AuthRole::find($id);
            $template['item'] = $user;
        }

        return view('auth_role.detail',$template);
    }

    public function save(Request $request){
        try{
            $app = is_null(Input::get("app")) ? 0 : 1;
            $user = session('user');
            $id = Input::get('id');
            $name = Input::get('name');
            

            if($id != 0) {
                $auth_role = AuthRole::find($id);
                $auth_role->updated_by = $user->id;
                $auth_role->updated_at = date('Y-m-d H:i:s');
            }
            else{
                $auth_role  = new AuthRole();
                $auth_role->status = 1;
                $auth_role->created_by = $user->id;
                $auth_role->created_at = date('Y-m-d H:i:s');
            }
            $auth_role->name = $name;
            $auth_role->app = $app;
            $auth_role->save();

            return response(json_encode(array("error" => 0,"id" => $auth_role->id)), 200);

        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

    public function perms($id){

        $perms = DB::select(DB::raw('select ao.id,ao.name,ao.father_id, auo.object_id
                                    from auth_object ao 
                                    left join auth_role_object auo on auo.object_id = ao.id and auo.role_id = '.$id.'
                                    order by ao.position'));

        $template = array(
            "menu_active" => "auth_role",
            "smenu_active" => "",
            "page_title" => "Roles",
            "page_subtitle" => "Permisos",
            "id" => $id,
            "items" => $perms,
            "user" => session('user')
        );

        return view('auth_role.perms',$template);
    }

    public function permissionsSave() {
        $user = session('user');

        if ($user) {
            $id = Input::get('id');
            $permissions = Input::get('perms_check');
            $objects_ids = Input::get('object_id');
            $data = [];

            AuthRoleObject::where('role_id',$id)->delete();
            for ($i = 0 ; $i < count($permissions); $i++) {
                if($permissions[$i] == "true")
                    $data[] = [
                        "role_id" => $id,
                        "object_id" => intval($objects_ids[$i])
                    ];
            }

            AuthRoleObject::insert($data);

            return response(array('error' => 0), 200);
        } else {

            return response("", 500);
        }
    }
}
