<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoginResource;
use App\Jobs\ForgotPasswordJob;
use App\Jobs\WelcomeMailJob;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Jerry\JWT\JWT;

class AuthController extends Controller
{
    public function register(Request $req){
        try {
            $user = User::where("email", $req->input("email"))->first();
            if ($user) {
                return response()->json(["status"=>false, "msg"=>"User is already exists"]);
            }

            $newUser = new User();
            $newUser->name = $req->input("name");
            $newUser->email = $req->input("email");
            $newUser->password = Hash::make($req->input("password"));

            if ($req->hasFile("profilePhoto")) {
                $image = $req->file("profilePhoto");
                $fileName = time()."_".$newUser->name."_".$image->getClientOriginalName();
                $path = $image->storeAs("profile_photos", $fileName, "public");
                $imageUrl = asset("storage/". $path);
                $newUser->profilePhoto = $imageUrl;
            }

            $newUser->save();

            WelcomeMailJob::dispatch($newUser);

            return response()->json($newUser);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function login(Request $req){
        try {
            $user = User::where("email", $req->input("email"))->first();
            if (!$user) {
                return response()->json(["status"=>false, "msg"=>"User is not found"]);
            }

            if (!Hash::check($req->input("password"), $user->password)) {
                return response()->json(["status"=>false, "msg"=>"incorrect Password"]);
            }

            $token = JWT::encode(["userId"=>$user->id]);
            Cache::put("token:".$user->id, $token, 3600*2);
            $user->remember_token = $token;
            $user->save();

            $userDetails = [
                "id"=>$user->id,
                "name"=>$user->name,
                "email"=>$user->email,
                "profilePhoto"=>$user->profilePhoto
            ];

            return (new LoginResource($userDetails))->additional(["token"=>$token]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function forgotPassword(Request $req){
        try {
            $user = User::where("email", $req->input("email"))->first();
            if (!$user) {
                return response()->json(["status"=>false, "msg"=>"User is not found"]);
            }

            $passToken = JWT::encode(["email"=>$user->email]);
            $user->forgotpasstoken = $passToken;
            Cache::put("forgotPassToken:".$user->id, $passToken, 900);
            $user->save();

            ForgotPasswordJob::dispatch(["email"=>$user->email, "passToken"=> $passToken]);

            return response()->json(["status"=>true, "msg"=>"Mail Sended"]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function newPassword(Request $req){
        try {
            $user = User::where("forgotpasstoken", $req->query("passtoken"))->first();
            if (!$user) {
                return response()->json(["status"=>false, "msg"=>"User is not found"]);
            }
            
            if ($user->forgotpasstoken !=  $req->query("passtoken") || $user->forgotpasstoken != Cache::get("forgotPassToken:".$user->id)) {
                return response()->json(["status"=>false, "msg"=>"Token is invalid"]);
            }

            $user->forgotpasstoken = "";
            $user->password = Hash::make($req->input("password"));
            $user->save();
            Cache::has("forgotPassToken:".$user->id) ?? Cache::pull("forgotPassToken:".$user->id);

            return response()->json(["status"=>true, "msg"=>"The Password changed successfully"]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function editProfile(Request $req){
        try {
            $user = User::where("email", $req->input("email"))->first();
            if (!$user) {
                return response()->json(["status"=>false, "msg"=>"User is not found"]);
            }

            $user->name = $req->input("name");
            $user->email = $req->input("email");
            $user->password = $req->input("password") ? Hash::make($req->input("password")) : $user->password;
            if ($req->hasFile("profilePhoto")) {
                $image = $req->file("profilePhoto");
                $fileName = time()."_".$user->name."_".$image->getClientOriginalName();
                $path = $image->storeAs("profile_photos", $fileName, "public");
                $imageUrl = asset("storage/". $path);
                $user->profilePhoto = $imageUrl;
            }
            $user->save();

            return response()->json(["status"=>true, "user"=>$user]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
