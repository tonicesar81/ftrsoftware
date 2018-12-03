<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\User_dados;

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
    protected $redirectTo = '/welcome';
    private function nivel(){
        $nivel = User_dados::where('users_id', Auth::id())->value('user_levels_id');
        if(!is_null($nivel)){
            return $nivel;
        }else{
            return null;
        }
    }
    
    public function redirectTo(){
        if(is_null($this->nivel())){
//            exit('teste');
            return '/welcome';
        }else{
            return '/home';
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
        
    public function username() {
        return 'username';
    }
}
