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

Route::get('exportar', 'UsersController@exportar');

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

Route::get('analise/relatorios_antigos', 'RelatoriosController@index_old');
Route::get('analise/relatorios_antigos/{id}', 'RelatoriosController@show_old');
Route::get('analise/relatorios_antigos/pdf/{id}', 'RelatoriosController@pdf_old');
Route::get('analise/relatorios_antigos/{id}/{loja}', 'RelatoriosController@lista_old');


Route::resource('analise/figuras', 'FigurasController');
Route::get('analise/figuras/create/{id}', 'FigurasController@create');

Route::resource('analise/projetos', 'ProjetosController');
Route::get('analise/projetos/download/{id}', 'ProjetosController@download');
Route::get('analise/projetos/revisao/{id}', 'ProjetosController@revisao');
Route::put('analise/projetos/revisao/{id}', 'ProjetosController@storeRevisao');

Route::get('analise/projetos/arquivo/{id}', 'ProjetosController@addArquivo');
Route::post('analise/projetos/arquivo/{id}', 'ProjetosController@storeArquivos');

Route::get('analise/projetos/detalhes/{id}', 'ProjetosController@detalhes');


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
Route::get('datasheets/create/{id?}', 'DatasheetsController@create');
Route::get('datasheets/edit/{id}', 'DatasheetsController@edit');

//Route::get('projetos/addFile/{id}', 'ProjetosController@addFile');

//-----MANUTENÇÃO--------
Route::resource('manutencao/itens', 'Man_itensController');
Route::get('manutencao/itens/edit/{id}', 'Man_itensController@edit');

Route::resource('manutencao/desc', 'Man_descController');
Route::get('manutencao/desc/edit/{id}', 'Man_descController@edit');

Route::resource('manutencao/pavimentos', 'PavimentosController');
Route::get('manutencao/pavimentos/edit/{id}', 'PavimentosController@edit');

Route::resource('manutencao/setores', 'SetoresController');
Route::get('manutencao/setores/create/{id}', 'SetoresController@create');
Route::get('manutencao/setores/edit/{id}', 'SetoresController@edit');

Route::resource('manutencao/instalacoes', 'InstalacoesController');
Route::get('manutencao/instalacoes/edit/{id}', 'InstalacoesController@edit');
Route::get('manutencao/instalacoes/includes/pavimento/{id}', 'InstalacoesController@pavimentos');
Route::get('manutencao/instalacoes/includes/setor/{id}', 'InstalacoesController@setores');

Route::resource('manutencao/estruturas', 'EstruturasController');
Route::get('manutencao/estruturas/create/{id}', 'EstruturasController@create');

Route::resource('manutencao/relatorios', 'Man_relatoriosController');
Route::get('manutencao/relatorios/instalacao/{shopping}/{item}', 'Man_relatoriosController@instalacao');
Route::get('manutencao/relatorios/lista/{id}', 'Man_relatoriosController@lista');
Route::get('manutencao/relatorios/edit/{id}', 'Man_relatoriosController@edit');
Route::get('manutencao/relatorios/duplicar/{id}', 'Man_relatoriosController@duplicar');
Route::get('manutencao/relatorios/pdf/{id}', 'Man_relatoriosController@pdf');

Route::resource('manutencao/entregas', 'EntregasController');
Route::get('manutencao/entregas/pdf/{id}', 'EntregasController@pdf');
Route::get('manutencao/entregas/servico/{id}', 'EntregasController@servico');
Route::post('manutencao/entregas/servico', 'EntregasController@storeServ');

Route::resource('manutencao/manuais', 'ManuaisController');

Route::resource('manutencao/capitulos', 'ManualCapitulosController');
Route::get('manutencao/capitulos/create/{id}', 'ManualCapitulosController@create');
Route::get('manutencao/capitulos/edit/{id}', 'ManualCapitulosController@edit');

Route::resource('manutencao/termos', 'TermosController');
Route::get('manutencao/termos/edit/{id}', 'TermosController@edit');

Route::resource('manutencao/obras', 'ObrasController');
Route::get('manutencao/obras/create/{id}', 'ObrasController@create');
Route::get('manutencao/obras/instalacao/{shopping}/{item}', 'ObrasController@instalacao');
Route::get('manutencao/obras/disciplina/{id}/{qnt}', 'ObrasController@disciplina');
Route::get('manutencao/obras/arquivos/{id}', 'ObrasController@arquivos');
Route::get('manutencao/arquivos/download/{id}', 'ObrasController@download');
Route::get('manutencao/obras/certificado/{id}', 'ObrasController@certificado');

Route::resource('manutencao/certificados', 'Obras_certificados_padraoController');

Route::resource('manutencao/arquivos', 'Obras_arquivosController');
Route::get('manutencao/arquivos/create/{obras_id}', 'Obras_arquivosController@create');

Route::resource('manutencao/padrao', 'ObrasTextosPadraoController');
