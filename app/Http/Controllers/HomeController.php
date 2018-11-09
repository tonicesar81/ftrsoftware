<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User_dados;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    private function nivel(){
        $nivel = User_dados::where('users_id', Auth::id())->value('user_levels_id');
        if(!is_null($nivel)){
            return $nivel;
        }else{
            return null;
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(is_null($this->nivel())){
            return redirect('analise/relatorios');
        }else{
            return redirect('analise/projetos');
        }
//        return view('home');
    }
}
