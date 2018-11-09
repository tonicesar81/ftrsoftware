<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Shopping;
use App\Empresa;
use App\User;
use Illuminate\Support\Facades\Validator;

class ShoppingsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }

    private function permission(){
        $nivel = DB::table('user_dados')->where('users_id', Auth::id())->value('user_levels_id');
        if(is_null($nivel)){
            abort(403, 'Acesso Negado');
        }
    }
    
    public function index(Request $request) {
        //
        $this->permission();
        $pesquisa = $request->pesquisa;
//        return var_dump($pesquisa);
        $shoppings = DB::table('shoppings')
                ->join('empresas', 'empresas.id', '=', 'shoppings.empresas_id')
                ->select('shoppings.*', 'empresas.empresa');

        if (!is_null($pesquisa)) {
            $shoppings->where('shopping', 'like', '%' . $pesquisa . '%');
        }
        $shoppings->orderBy('shoppings.id', 'asc');
        $shoppings = $shoppings->paginate(50);
        return view('shoppings.index', ['shoppings' => $shoppings]);
    }
    
    public function pesquisa(Request $request){
        $this->permission();
        $pesquisa = $request->pesquisa;
        
        $shoppings = DB::table('shoppings')
                ->join('empresas', 'empresas.id', '=', 'shoppings.empresas_id')
                ->select('shoppings.*', 'empresas.empresa');

        if (!is_null($pesquisa)) {
            $shoppings->where('shopping', 'like', '%' . $pesquisa . '%');
        }
        $shoppings->orderBy('shoppings.id', 'asc');
        $shoppings = $shoppings->get();
        return view('shoppings.index', ['shoppings' => $shoppings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $this->permission();
        //
        $empresas = Empresa::all();
        $users = User::all();
        return view('shoppings.create', ['empresas' => $empresas, 'users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->permission();
        //
        $this->validate($request, [
            'shopping' => 'required|unique:shoppings'
        ]);
        $shopping = new Shopping;
        $shopping->empresas_id = $request->empresas_id;
        $shopping->shopping = $request->shopping;

        $shopping->save();

        foreach ($request->users as $user) {
            DB::table('users_shoppings')->insert(
                    ['shoppings_id' => $shopping->id, 'users_id' => $user]
            );
        }

        return redirect('shoppings')->with('message', 'Shopping cadastrado com sucesso!');
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
        $this->permission();
        $empresas = Empresa::all();
        $shopping = Shopping::find($id);
        return view('shoppings.edit', ['shopping' => $shopping, 'empresas' => $empresas]);
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
        $this->permission();
        $shopping = Shopping::find($id);
        $this->validate($request, [
            'shopping' => 'required|unique:shoppings,shopping,' . $shopping->id
        ]);
//        Validator::make($request, [
//            'shopping' => [
//                'required',
//                Rule::unique('shoppings')->ignore($shopping->id,'shopping'),
//            ],
//        ]);
        $shopping->shopping = $request->shopping;
        $shopping->empresas_id = $request->empresas_id;
        $shopping->save();

        return redirect('shoppings')->with('message', 'Shopping alterado com sucesso');
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
        $shopping = Shopping::find($id);
        $shopping->delete();
        return redirect('shoppings')->with('message', 'Shopping removido com sucesso');
    }

}
