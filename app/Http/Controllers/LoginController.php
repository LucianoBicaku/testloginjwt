<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SupplierPasswordRecovery;

class LoginController extends Controller
{
    public function test(Request $request) {

       $customerOrders = DB::table('APP.Users')
                            ->where('IdUser', 'like', 'F90C1C93-E47D-43B7-8B34-4FCC542D5DCD')
                            ->get();
        return $customerOrders;
    }

    public function login(Request $request){

        $username = $request->input('username');
        $password = $request->input('password');

        if (empty($username) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all the fields']);
        }
        $hashedPassword = base64_encode(md5($password,true));
        $token = auth()->attempt(array('username' => $username, 'password' => $hashedPassword));
     
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        //return $this->respondWithToken($token);
        // return (array('username' => $username, 'password' => $hashedPassword));
         return ["token: ",$token];
    }

    public function me()
    {
        return response()->json(auth());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
