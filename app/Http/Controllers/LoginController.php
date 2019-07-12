<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Models\AuthUser;
use App\Models\AuthRoleObject;
use Redirect;

class LoginController extends BaseController {
    
     

    public function login(Request $request) {
        $info = array(
            'error' => 0,
            'error_msj' => ''
        );

        $user = session('user');

        if ($user) {
            foreach ($user->permissions as $k => $v) {
                if ($v['location'] != '') {
                    return redirect($v['location']);
                    break;
                }
            }
        }
        if ($request->isMethod('post')) {

            $user = AuthUser::where('user', '=', $request->username)
                    ->first();
            if ($user) {
                if ($user->password === $request->password) {

                    $permissions = AuthRoleObject::select('auth_object.*', 'auth_role_object.permission')
                            ->join('auth_object', 'auth_object.id', '=', 'auth_role_object.object_id')
                            ->where('auth_role_object.role_id', $user->role_id)
                            ->where('auth_object.status', 1)
                            ->orderBy('auth_object.father_id', 'asc')
                            ->orderBy('auth_object.position', 'asc')
                            ->get();
                    if ($permissions) {
                        AuthUser::where('id', $user->id)->update(['last_at' => \Illuminate\Support\Facades\DB::raw('now()')]);
                        $data_perms = array();
                        foreach ($permissions as $k => $v) {

                            if ($v->father_id == 0) {
                                $data_perms[$v->id] = array(
                                    'id' => $v->id,
                                    'name' => $v->name,
                                    'menu_active' => $v->menu_active,
                                    'location' => $v->location,
                                    'position' => $v->position,
                                    'icon' => $v->icon,
                                    'permission' => $v->permission,
                                    'children' => array(),
                                );
                            } else {
                                if (isset($data_perms[$v->father_id])) {
                                    $data_perms[$v->father_id]['children'][$v->id] = array(
                                        'id' => $v->id,
                                        'name' => $v->name,
                                        'menu_active' => $v->menu_active,
                                        'location' => $v->location,
                                        'position' => $v->position,
                                        'permission' => $v->permission,
                                        'icon' => $v->icon,
                                    );
                                }
                            }
                        }
                        $user->permissions = $data_perms;
                        session()->put('user', $user);
                        foreach ($data_perms as $k => $v) {
                            if ($v['location'] != '') {
                                return redirect($v['location']);
                                break;
                            }
                        }
                    } else {
                        $message = "Usuario sin permisos";
                        return Redirect::route("admin_index")->withInput(Input::all())->with("message", $message);
                    }
                } else {
                    $message = "Usuario y/o password incorrectos";
                        return Redirect::route("admin_index")->withInput(Input::all())->with("message", $message);
                }
            } else {
                $message = "Usuario y/o password incorrectos";
                        return Redirect::route("admin_index")->withInput(Input::all())->with("message", $message);
            }
        }

        return view('login.login', $info);
    }

    public function logout(Request $request) {
        session()->forget('user');
        return redirect()->route('admin_index');
    }

}
