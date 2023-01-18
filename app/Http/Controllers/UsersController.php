<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Constants\RES;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * @OA\Post(
     *      tags={"Authentication"},
     *      summary="User login",
     *      path="/api/users/login",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "email":"teste@teste.com",
     *                     "password":"examplepassword123"
     *                }
     *             )
     *         )
     *      ),
     *  )
     */
    public function login(LoginRequest $req)
    {
        // Found user
        if (!$user = User::where('email', $req->email)->first()) {
            return RES::NOTFOUND("User not found");
        }

        // Check password
        if (!Hash::check($req->password, $user->password)) {
            return RES::UNAUTHORIZED("Password is wrong");
        }

        // Return access token
        return RES::OK($user->createToken($req->userAgent())->plainTextToken);
    }

    /**
     * @OA\Post(
     *      tags={"Authentication"},
     *      summary="User registrarion",
     *      path="/api/users/register",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "name":"User name",
     *                     "email":"teste@teste.com",
     *                     "password":"examplepassword123"
     *                }
     *             )
     *         )
     *      ),
     *  )
     */
    public function register(RegisterRequest $req)
    {
        $passwordHash = password_hash($req->password, PASSWORD_BCRYPT);

        try{
            $user = User::create([
                "name" => $req->name,
                "email" => $req->email,
                "username" => $req->username,
                "password" => $passwordHash
            ]);
        } catch (Exception $e) {
            return RES::BADREQUEST("An unexpected error ocurred");
        }


        return RES::OK($user);
    }

    public function me(Request $req) {
        $user = User::find((Auth::user())->id);
        return $user;
    }
}
