<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class AuthVerification
{
   public function handle($request, Closure $next)
   {;
        if(!is_null($request->segment(1))) {
            $user = session('user');
            if (!isset($user)) {
                return \redirect()->route('admin_index');
            } else {
                $key = array_search($request->segment(1), array_column($user->permissions, 'menu_active'));
                if (!is_numeric($key)){
                    return \redirect()->route(array_values($user->permissions)[0]['menu_active']);
                }
            }
        }

        return $next($request);
    }
}
