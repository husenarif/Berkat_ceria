<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;

class VerifyPemilik
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $role_id = $request->user()->role_id;
        $pemilikId = Role::where('role_name','pemilik')->first()->id;

        if($role_id != $pemilikId){
            return abort(403, 'Anda Tidak Memiliki Akses');
        }

        return $next($request);
    }
}
