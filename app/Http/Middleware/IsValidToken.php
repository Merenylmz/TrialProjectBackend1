<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Jerry\JWT\JWT;
use Symfony\Component\HttpFoundation\Response;

class IsValidToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $req, Closure $next): Response
    {
        $decodedToken = JWT::decode($req->query("token"));
        $user = User::find($decodedToken["userId"]);
        if (!$user) {
            return response()->json(["status"=>false, "msg"=>"User is not found"]);
        }

        else if ($req->query("token") != $user->remember_token || Cache::get("token:".$user->id) != $user->remember_token) {
            return response()->json(["status"=>false, "msg"=>"Token is invalid"]);
        }
        $req->attributes->set("userId", $user->id);

        return $next($req);
    }
}
