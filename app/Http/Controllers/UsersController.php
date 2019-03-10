<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\User;
use App\Empresa;
use App\User_level;
use App\User_dados;
use App\Shopping;
use Illuminate\Support\Facades\Storage;
use App\tipo_relatorios;
use App\Users_responsaveis;

use App\Mail\NewUser;

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
    private function permission(){
        $nivel = DB::table('user_dados')->where('users_id', Auth::id())->value('user_levels_id');
        if(is_null($nivel)){
            abort(403, 'Acesso Negado');
        }
    }


    public function index() {
//        SELECT u.name, s.shopping
//        FROM users as u
//        INNER JOIN shoppings as s ON s.id IN (SELECT shoppings_id FROM users_shoppings WHERE users_id = u.id)
//       
        $this->permission();
        $funcionarios = User_dados::pluck('users_id');
        $users = User::whereNotIn('id',$funcionarios)
                ->paginate(50);
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
    
    public function funcionarios() {
//            SELECT u.id,u.name,u.username,u.email,l.nivel,u.created_at FROM user_dados AS d
//            INNER JOIN users AS u ON d.users_id = u.id
//            INNER JOIN user_levels AS l ON d.user_levels_id = l.id
//            WHERE l.id <> 1
        $this->permission();
        $users = DB::table('user_dados as d')
                ->join('users as u', 'd.users_id', '=', 'u.id')
                ->join('user_levels as l', 'd.user_levels_id', '=', 'l.id')
                ->select('u.id', 'u.name', 'u.username', 'u.email', 'l.nivel', 'u.created_at')
                ->where('l.id','<>', 1)
                ->paginate(50);
//        $users = User::paginate(50);
//        foreach ($users as $user) {
//
//            $users->map(function ($user) {
////              $u = DB::table('users_shoppings')->where('users_id',$user->id)->get()->pluck('shoppings_id');
//                $shoppings = Shopping::whereIn('id', DB::table('users_shoppings')->where('users_id',$user->id)->get()->pluck('shoppings_id'))->get();
//                $user['shoppings'] = $shoppings;
//                return $user;
//            });
//        }
        return view('users.index', ['users' => $users]);
    }
    
    public function pesquisaFuncionario(Request $request){
        $this->permission();
        $pesquisa = $request->pesquisa;
//        return var_dump($pesquisa);
//        $users = User::where([
//                ['name','like','%'.$pesquisa.'%'],
//                ['l.id','<>', 1]
//                ]
//                )->get();
//        return $users->toSql();
//        $users = DB::table('users')->where('name','like','%'.$pesquisa.'%')->get();
        $users = DB::table('user_dados as d')
                ->join('users as u', 'd.users_id', '=', 'u.id')
                ->join('user_levels as l', 'd.user_levels_id', '=', 'l.id')
                ->select('u.id', 'u.name', 'u.username', 'u.email', 'l.nivel', 'u.created_at')
                ->where([
                        ['name','like','%'.$pesquisa.'%'],
                        ['l.id','<>', 1]
                    ]
                )->get();
//        foreach ($users as $user) {
//
//            $users->map(function ($user) {
////              $u = DB::table('users_shoppings')->where('users_id',$user->id)->get()->pluck('shoppings_id');
//                $shoppings = Shopping::whereIn('id', DB::table('users_shoppings')->where('users_id',$user->id)->get()->pluck('shoppings_id'))->get();
//                $user['shoppings'] = $shoppings;
//                return $user;
//            });
//        }
        return view('users.index', ['users' => $users]);
//        return view('shoppings.index', ['shoppings' => $shoppings]);
    }
    
    public function pesquisa(Request $request){
        $this->permission();
        $pesquisa = $request->pesquisa;
//        return var_dump($pesquisa);
        $funcionarios = User_dados::pluck('users_id');
//        return print_r($funcionarios);
        $users = User::where('name','like','%'.$pesquisa.'%')
                ->whereNotIn('id',$funcionarios)
                //->whereRaw('id NOT IN ('.implode(',',$funcionarios).')')
                ->get();
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
    
    public function exportar(){
        $this->permission();
        $users_old = DB::table('users_copy')
                ->where('user_levels_id', 99)
                ->get();
        foreach($users_old as $o_user){
            $user = new User;
            $user->name = $o_user->name;
            $user->username = $o_user->username;
            $user->email = $o_user->email;
            $user->password = $o_user->password;
            $user->created_at = $o_user->created_at;
            $user->save();
            if(!is_null($o_user->shoppings)){
                $shoppings = explode(',', $o_user->shoppings);
                foreach($shoppings as $s){
                    DB::table('users_shoppings')->insert(
                        ['shoppings_id' => $s, 'users_id' => $user->id]
                    );
                }
            }
        }
    }
    public function create() {
        //
        $this->permission();
        
        $shoppings = Shopping::orderBy('shopping', 'ASC')->get();
        return view('users.create', ['shoppings' => $shoppings]);
    }
    public function createFuncionario() {
            //
            $this->permission();
//            $shoppings = Shopping::orderBy('shopping', 'ASC')->get();
            $tipo_relatorios = tipo_relatorios::where('id', '<>', 4)->get();
            $levels = User_level::where('id', '<>', 1)->get();
            return view('users.create', ['levels' => $levels, 'tipo_relatorios' => $tipo_relatorios]);
        }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
        $this->permission();
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
        $redir = 'users';
        if($request->has('funcionario')){
            $redir = 'funcionarios';
            $dados = new User_dados;
            $dados->users_id = $user->id;
            if($request->has('titulo')){
                $dados->titulo = $request->titulo;
            }
            $dados->user_levels_id = $request->user_levels_id;
            if($request->hasFile('assinatura')){
                $dados->assinatura = Storage::disk('public')->put('assinaturas', $request->file('assinatura'));
            }
            $dados->save();
        }
        if($request->responsavel){
            $telefone = $request->telefone;
            $users_id = $user->id;
            if($request->hasFile('assinatura')){
                $assinatura = Storage::disk('public')->put('assinaturas', $request->file('assinatura'));
            }else{
                $assinatura = null;
            }
            DB::table('users_responsaveis')->insert(
                    ['users_id' => $users_id, 'telefone' => $telefone, 'assinatura' => $assinatura]
            );
        }
        if( $request->has('shoppings')){
            foreach($request->shoppings as $shopping){
                DB::table('users_shoppings')->insert(
                        ['shoppings_id' => $shopping, 'users_id' => $user->id]
                );
            }
        }
        if( $request->has('disciplinas')){
            foreach($request->disciplinas as $disciplina){
                DB::table('users_disciplinas')->insert(
                        ['tipo_relatorios_id' => $disciplina, 'users_id' => $user->id]
                );
            }
        }
        if ($request->filled('email')) {
//            $dados = collect(['name' => $request->name, 'username' => $request->username, 'password' => $pw]);
            try {
                Mail::to($request->email)->send(new NewUser());
            } catch (\Exception $e) {
                return redirect($redir)->with('message', 'Usuário cadastrado com sucesso, mas o E-mail não pôde ser enviado');
            }
        }
        //$user->notify(new CadastroMessage($user->id, $dados));


        return redirect($redir)->with('message', 'Usuário cadastrado com sucesso');
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
    public function dados(){
        $id = Auth::id();
        $user = User::find(Auth::id());
        $dados = DB::table('user_dados')->where('users_id', Auth::id())->first();
        $funcionario = DB::table('user_dados')->where('users_id', Auth::id())->first();
        $array['user'] = $user;
        if(!is_null($dados)){
            $array['dados'] = $dados;
        }else{
//            $array['shoppings'] = Shopping::orderBy('shopping', 'ASC')->get();
//            $array['tags'] = DB::table('shoppings')
//                ->whereIn('id',DB::table('users_shoppings')->where('users_id',$id)->pluck('shoppings_id'))
//                ->get();
        }
        if(!is_null($funcionario)){
            $array['funcionario'] = $funcionario;
            $array['tipo_relatorios'] = tipo_relatorios::where('id', '<>', 4)->get();
            $array['tags'] = DB::table('tipo_relatorios')
                    ->whereIn('id',DB::table('users_disciplinas')->where('users_id',$id)->pluck('tipo_relatorios_id'))
                    ->get();
        }
        
        return view('users.edit',$array);
    }
    
    
    
    public function edit($id) {
        //
        $this->permission();
        $user = User::find($id);
        $dados = User_dados::where('users_id', Auth::id())->first();
        
        $funcionario = User_dados::where('users_id', $id)->first();
                        
        $shoppings = Shopping::orderBy('shopping', 'ASC')->get();
        //SELECT id, shopping FROM shoppings WHERE id IN(SELECT shoppings_id FROM users_shoppings WHERE users_id = 2)
        if(is_null($funcionario)){
            $tags = DB::table('shoppings')
                    ->whereIn('id',DB::table('users_shoppings')->where('users_id',$id)->pluck('shoppings_id'))
                    ->get();
        }else{
            $tags = DB::table('tipo_relatorios')
                    ->whereIn('id',DB::table('users_disciplinas')->where('users_id',$id)->pluck('tipo_relatorios_id'))
                    ->get();
        }
//        if(($user->id != Auth::id()) && (Auth::user()->user_levels_id > 2)){
//            abort(403, 'Acesso Negado!');
//            //return "erro aqui!";
//        }
//        if(($user->user_levels_id == 1) && (Auth::user()->user_levels_id > 1)){
//            abort(403, 'Acesso Negado!');
//        }
        $levels = User_level::where('id', '<>', 1)->get();
        $tipo_relatorios = tipo_relatorios::where('id', '<>', 4)->get();
        $responsavel = DB::table('users_responsaveis')->where('users_id', $id)->first();
        $array = [
            'user' => $user, 
            'shoppings' => $shoppings, 
            'tags' => $tags, 
            'dados' => $dados, 
            'funcionario' => $funcionario, 
            'levels' => $levels, 
            'tipo_relatorios' => $tipo_relatorios,
            'responsavel' => $responsavel
                ];
        return view('users.edit', $array);
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
        $redir = 'users';
        $dados = User_dados::where('users_id', $id)->first();
        if(!is_null($dados)){
            $redir = 'funcionarios';
            if($request->has('titulo')){
                $dados->titulo = $request->titulo;
            }
            if($request->hasFile('assinatura')){
                $dados->assinatura = Storage::disk('public')->put('assinaturas', $request->file('assinatura'));
            }
            $dados->save();
        }
        DB::table('users_shoppings')->where('users_id', '=', $user->id)->delete();
        
        if($request->responsavel){
//            DB::table('users_responsaveis')->where('users_id', '=', $user->id)->delete();
            $telefone = $request->telefone;
            $users_id = $user->id;
            $array = ['users_id' => $users_id, 'telefone' => $telefone];
            if($request->hasFile('assinatura')){
                $assinatura = Storage::disk('public')->put('assinaturas', $request->file('assinatura'));
                $array['assinatura'] = $assinatura;
            }else{
                $assinatura = null;
            }
            
            Users_responsaveis::updateOrCreate(
                    ['users_id' => $users_id],
                    $array
            );
        }
        if( $request->has('shoppings')){
            foreach($request->shoppings as $shopping){
                DB::table('users_shoppings')->insert(
                        ['shoppings_id' => $shopping, 'users_id' => $user->id]
                );
            }
        }
        
        if( $request->has('disciplinas')){
            DB::table('users_disciplinas')->where('users_id', '=', $user->id)->delete();
            foreach($request->disciplinas as $disciplina){
                DB::table('users_disciplinas')->insert(
                        ['tipo_relatorios_id' => $disciplina, 'users_id' => $user->id]
                );
            }
        }
        
        if(Auth::id() == $id){
            return redirect()->back()->with('message', 'Dados cadastrais alterados com sucesso.'); 
        }else{
            return redirect($redir)->with('message', 'Usuário alterado com sucesso');            
        }
        
        

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
        $this->permission();
        $user = User::find($id);

        $user->delete();

        return redirect()->back()->with('message', 'Usuário removido com sucesso');
    }

}
