<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('home');
//});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/welcome', function(){
    return view('welcome');
})->middleware('auth');

Route::get('manuais/{filename}', function ($filename)
{
//    $file = storage_path('app') . '/' . $filename; // or wherever you have stored your PDF files
    $file = asset('manuais/'.$filename);
    return response()->file($file);
});

Route::resource('empresas', 'EmpresasController');
Route::get('empresas/edit/{id}', 'EmpresasController@edit');

Route::resource('shoppings', 'ShoppingsController');
Route::get('shoppings/edit/{id}', 'ShoppingsController@edit');
Route::post('shoppings/pesquisa', 'ShoppingsController@pesquisa');

Route::resource('users', 'UsersController');

Route::get('users/edit/{id}', 'UsersController@edit');
Route::post('users/pesquisa', 'UsersController@pesquisa');

Route::get('cadastro', 'UsersController@dados');
Route::get('funcionarios', 'UsersController@funcionarios');
Route::get('funcionarios/create', 'UsersController@createFuncionario');
Route::post('funcionarios/pesquisa', 'UsersController@pesquisaFuncionario');

Route::resource('analise/sistema', 'TipoRelatoriosController');
Route::get('analise/sistema/edit/{id}', 'TipoRelatoriosController@edit');

Route::resource('analise/item', 'ItensController');
Route::get('analise/item/edit/{id}', 'ItensController@edit');

Route::resource('analise/obs', 'ListaAnalisesController');
Route::get('analise/obs/edit/{id}', 'ListaAnalisesController@edit');

Route::resource('analise/relatorios', 'RelatoriosController');
Route::get('analise/relatorios/{id}', 'RelatoriosController@show');
Route::get('analise/relatorios/pdf/{id}', 'RelatoriosController@pdf');
Route::get('analise/relatorios/create/{id}/{inc?}', 'RelatoriosController@create');
Route::get('analise/relatorios/edit/{id}', 'RelatoriosController@edit');
Route::get('analise/relatorios/revisao/{id}/{projeto_id?}', 'RelatoriosController@edit');
Route::put('analise/relatorios/revisao/{id}', 'RelatoriosController@saveRevisao');
Route::get('analise/relatorios/{id}/{loja}', 'RelatoriosController@lista');
Route::get('analise/relatorios/disciplina/{id}/{inc?}', 'RelatoriosController@disciplina');


Route::resource('analise/figuras', 'FigurasController');
Route::get('analise/figuras/create/{id}', 'FigurasController@create');

Route::resource('analise/projetos', 'ProjetosController');
Route::get('analise/projetos/download/{id}', 'ProjetosController@download');
Route::get('analise/projetos/revisao/{id}', 'ProjetosController@revisao');
Route::put('analise/projetos/revisao/{id}', 'ProjetosController@storeRevisao');

Route::resource('analise/grupos', 'GruposController');
Route::get('analise/grupos/edit/{id}', 'GruposController@edit');

Route::resource('analise/normas', 'NormasController');
Route::get('analise/normas/edit/{id}', 'NormasController@edit');

Route::resource('arquivos', 'ArquivosController');
Route::get('arquivos/download/{id}', 'ArquivosController@download');
Route::get('arquivos/{id}/{loja}', 'ArquivosController@lista');

Route::resource('textos', 'ObjetivosController');

Route::resource('datasheets/nomes', 'DsnomesController');
Route::get('datasheets/nomes/edit/{id}', 'DsnomesController@edit');

Route::resource('datasheets/tipos', 'DstiposController');
Route::get('datasheets/tipos/edit/{id}', 'DstiposController@edit');

Route::resource('datasheets/localidades', 'DslocaisController');
Route::get('datasheets/localidades/edit/{id}', 'DslocaisController@edit');

Route::resource('datasheets', 'DatasheetsController');
Route::get('datasheets/edit/{id}', 'DatasheetsController@edit');

//Route::get('projetos/addFile/{id}', 'ProjetosController@addFile');
