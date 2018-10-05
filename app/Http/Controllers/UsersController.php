<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\User;
use App\Empresa;
//use App\User_level;
use App\Shopping;

//use App\Mail\NewUser;

class UsersController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }

//    private function permission(){
//        if(!in_array(Auth::user()->user_levels_id,[1,2])){
//            abort(403, 'Acesso Negado');
//        }
//    }


    public function index() {
//        SELECT u.name, s.shopping
//        FROM users as u
//        INNER JOIN shoppings as s ON s.id IN (SELECT shoppings_id FROM users_shoppings WHERE users_id = u.id)
//        
        $users = User::paginate(50);
        foreach ($users as $user) {

            $users->map(function ($user) {
//              $u = DB::table('users_shoppings')->where('users_id',$user->id)->get()->pluck('shoppings_id');
                $shoppings = Shopping::whereIn('id', DB::table('users_shoppings')->where('users_id',$user->id)->get()->pluck('shoppings_id'))->get();
                $user['shoppings'] = $shoppings;
                return $user;
            });
        }
        return view('users.index', ['users' => $users]);
    }
    
    public function pesquisa(Request $request){
        $pesquisa = $request->pesquisa;
//        return var_dump($pesquisa);
        $users = User::where('name','like','%'.$pesquisa.'%')->get();
//        return $users->toSql();
//        $users = DB::table('users')->where('name','like','%'.$pesquisa.'%')->get();
        foreach ($users as $user) {

            $users->map(function ($user) {
//              $u = DB::table('users_shoppings')->where('users_id',$user->id)->get()->pluck('shoppings_id');
                $shoppings = Shopping::whereIn('id', DB::table('users_shoppings')->where('users_id',$user->id)->get()->pluck('shoppings_id'))->get();
                $user['shoppings'] = $shoppings;
                return $user;
            });
        }
        return view('users.index', ['users' => $users]);
//        return view('shoppings.index', ['shoppings' => $shoppings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
//        $this->permission();
        $shoppings = Shopping::orderBy('shopping', 'ASC')->get();
        return view('users.create', ['shoppings' => $shoppings]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
//        $this->permission();
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'nullable|email|unique:users'
        ]);
        $user = new User;

        $user->name = $request->name;
        $user->username = $request->username;
        
        $user->email = $request->email;
        if ($request->filled('password')) {
            $pw = $request->password;
            $user->password = bcrypt($request->password);
        } else {
            $pw = '123456';
            $user->password = bcrypt('123456');
//            $user->pw_default = 1;
        }
//        $user->user_levels_id = $request->user_levels_id;
//        if(isset($request->shoppings)){
//            $user->shoppings = implode(',',$request->shoppings);
//        }
        $dados = array('name' => $request->name, 'username' => $request->username, 'password' => $pw);
        $user->save();
        
        if( $request->has('shoppings')){
            foreach($request->shoppings as $shopping){
                DB::table('users_shoppings')->insert(
                        ['shoppings_id' => $shopping, 'users_id' => $user->id]
                );
            }
        }
        if ($request->filled('email')) {
//            $dados = collect(['name' => $request->name, 'username' => $request->username, 'password' => $pw]);
            try {
                Mail::to($request->email)->send(new NewUser());
            } catch (\Exception $e) {
                return redirect('users')->with('message', 'Usuário cadastrado com sucesso, mas o E-mail não pôde ser enviado');
            }
        }
        //$user->notify(new CadastroMessage($user->id, $dados));


        return redirect('users')->with('message', 'Usuário cadastrado com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
        $user = User::find($id);
        $shoppings = Shopping::orderBy('shopping', 'ASC')->get();
        //SELECT id, shopping FROM shoppings WHERE id IN(SELECT shoppings_id FROM users_shoppings WHERE users_id = 2)
        $tags = DB::table('shoppings')
                ->whereIn('id',DB::table('users_shoppings')->where('users_id',$id)->pluck('shoppings_id'))
                ->get();
//        if(($user->id != Auth::id()) && (Auth::user()->user_levels_id > 2)){
//            abort(403, 'Acesso Negado!');
//            //return "erro aqui!";
//        }
//        if(($user->user_levels_id == 1) && (Auth::user()->user_levels_id > 1)){
//            abort(403, 'Acesso Negado!');
//        }

        return view('users.edit', ['user' => $user, 'shoppings' => $shoppings, 'tags' => $tags]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
        $user = User::find($id);
        $this->validate($request, [
            'name' => 'required',
            'email' => 'nullable|email|unique:users,email,' . $user->id
        ]);
        $user->username = $user->username;        
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->password != null) {
            $user->password = bcrypt($request->password);
//            $user->pw_default = 0;
        }

        $user->save();
        
        DB::table('users_shoppings')->where('users_id', '=', $user->id)->delete();
        
        if( $request->has('shoppings')){
            foreach($request->shoppings as $shopping){
                DB::table('users_shoppings')->insert(
                        ['shoppings_id' => $shopping, 'users_id' => $user->id]
                );
            }
        }
        
        return redirect('users')->with('message', 'Usuário alterado com sucesso');

//        if (in_array(Auth::user()->user_levels_id, [1, 2])) {
//            return redirect('users')->with('message', 'Usuário alterado com sucesso');
//        } else {
//            return redirect('home')->with('message', 'Usuário alterado com sucesso');
//        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
//        $this->permission();
        $user = User::find($id);

        $user->delete();

        return redirect('users')->with('message', 'Usuário removido com sucesso');
    }

}
