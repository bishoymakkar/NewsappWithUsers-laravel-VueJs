<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'favourites' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->json()->get('name'),
            'email' => $request->json()->get('email'),
            'password' => Hash::make($request->json()->get('password')),
            'favourites' => $request->json()->get('favourites'),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->json()->all();

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }

    public function updatefav(Request $request, User $user)
    {
        $ob = $request->get('headline');
        $userId = $request->get('user_id');
        $user = User::find($userId);
        $flag = 0;
        $fav = $user->favourites;
        for ($i = 0; $i < count($fav); $i++) {
            if ($fav[$i] == $ob) {
                $flag = 1;
                break;
            }
        }
        if ($flag == 0) {
            if ($user->favourites != null) {

                array_push($fav, $ob);
                $user->favourites = $fav;
            } else {
                $fav = [];
                array_push($fav, $ob);
                $user->favourites = $fav;
            }
            $user->save();
        } else {
            return "Match objects";
        }
    }
    public function checkfav(Request $request, User $user)
    {
        $ob = $request->get('headline');
        $userId = $request->get('user_id');
        $user = User::find($userId);
        $flag = 0;
        $fav = $user->favourites;
        for ($i = 0; $i < count($fav); $i++) {
            if ($fav[$i] == $ob) {
                $flag = 1;
                break;
            }
        }
        if ($flag == 1) {
            return true;
        } else {
            return false;
        }
    }
    public function removefav(Request $request, User $user)
    {
        $ob = $request->get('headline');
        $userId = $request->get('user_id');
        $user = User::find($userId);
        $flag = 0;
        $fav = $user->favourites;
        for ($i = 0; $i < count($fav); $i++) {
            if ($fav[$i] == $ob) {
                $flag = $i;
                break;
            }
        }
        array_splice($fav, $flag, 1);
        $user->favourites = $fav;
        $user->save();
        return $user;
    }
}
