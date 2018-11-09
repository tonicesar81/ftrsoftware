<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Figuras;

class FigurasController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }
    
    private function permission(){
        if(Auth::user()->user_levels_id > 3){
            abort(403);
        }
    }

    public function index() {
        //
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id) {
        //
        $this->permission();
        $relatorio = DB::table('relatorios')
                ->join('shoppings', 'shoppings.id', '=', 'relatorios.shoppings_id')
                ->join('users', 'users.id', '=', 'relatorios.users_id')
                ->select('relatorios.id', 'relatorios.loja', 'shoppings.shopping', 'relatorios.revisao')
                ->where('relatorios.id', $id)
                ->first();

        return view('analise.figuras.create', ['relatorio' => $relatorio]);
    }

    private function base64_to_jpeg($base64_string) {
        // open the output file for writing
        $output_file = md5(uniqid(rand(), true));
//        $ifp = fopen(storage_path('app\public\figuras\\' . $output_file . '.jpg'), 'wb');
        $ifp = fopen(public_path('storage\figuras\\' . $output_file . '.jpg'), 'wb');
//        return public_path('storage\\' . $output_file . '.jpg');
        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $base64_string);

        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($data[1]));

        //imagejpeg($ifp, public_path('figura/' . $output_file), 100);
        // clean up the file resource
        fclose($ifp);


        return $output_file . '.jpg';
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
//        return $request;
        $figura = new Figuras;
        $figura->relatorios_id = $request->relatorios_id;
//        return $request;
        if ($request->hasFile('figura')) {
            //

//            $figura->relatorios_id = $request->relatorios_id;
            $figura->figura = Storage::put('figuras', $request->file('figura'));
            $figura->save();
        } else {

//            $destImage = $this->base64_to_jpeg($request->figura);
//            //return($request->figura);
//            $figura->relatorios_id = $request->relatorios_id;
//            //return $destImage;
//            $figura->figura = 'figuras/' . $destImage;
            
            //get the base-64 from data
            $base64_str = substr($request->figura, strpos($request->figura, ",") + 1);

            //decode base64 string
            $image = base64_decode($base64_str);
            $output = 'figuras/'.md5(uniqid(rand(), true)).'.png';
            Storage::disk('public')->put($output, $image);
            $figura->figura = $output;
            //$storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

            $figura->save();
        }

        return redirect('analise/relatorios')->with('message', 'Figura demonstrativa anexada ao relatório');
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
        abort(404);
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
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
        abort(403);
    }

}
