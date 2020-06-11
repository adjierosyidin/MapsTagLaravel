<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function apilogin(Request $request)
    {
        if (\Auth::attempt(
            [
                'email' => $request->email,
                'password' => $request->password
            ]))
            {
                $user = \Auth::user();
                $token = $user->createToken('user')->accessToken;
                $data['user'] = $user;
                $data['token'] = $token;

                return response()->json(
                    [
                        'success' => true,
                        'data' => $data,
                        'message' => 'Login Berhasil'
                    ]
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'data' => 'q',
                        'message' => 'Login Gagal'
                    ]
                );
            }
    }

    public function apilogout(Request $request)
    {
        $user = Auth::guard('api')->user();

        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return response()->json(['data' => 'User logged out.'], 200);
    }
}
