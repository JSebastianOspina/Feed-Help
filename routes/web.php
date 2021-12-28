<?php

use Illuminate\Support\Facades\Route;

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

//SECCION DE NOTICIAS, QUE ESTAN EN LA PAGINA PRINCIPAL
Route::get('/', 'NewsController@index')->middleware('auth')->name('news.index');
Route::post('/news', 'NewsController@store')->middleware('auth', 'isOwner')->name('news.store');
Route::delete('/news/{id}', 'NewsController@destroy')->middleware('auth', 'isOwner')->name('news.delete');

//Resource deck, crear, editar, actualizar, borrar
Route::resource('decks', 'DeckController')->middleware(['auth']);
/* ---------------GESTIÓN INDIVIDUAL DEL DECK---------------- */

//Añadir usuario al deck
Route::post('decks/{deckId}/users', 'DeckController@newUser')->middleware('auth')->name('decks.users.store');
//Borrar usuario del deck
Route::delete('decks/{deckId}/user/{userId}', 'DeckController@deleteUser')->middleware('auth')->name('decks.users.delete');

//Administración del Deck (APIS)
Route::post('decks/{deckId}/apis', 'DeckController@storeApi')->middleware('auth')->name('decks.apis.store');
Route::patch('decks/{deckId}/apis/{apiId}', 'DeckController@updateApi')->middleware('auth')->name('decks.apis.patch');
Route::delete('decks/{deckId}/apis/{apiId}', 'DeckController@deleteApi')->middleware('auth')->name('decks.apis.delete');


Route::get('/testapi', 'twitter\TwitterController@buildAuthorizeURL');

Route::get('/theme', function () {
    return view('vuexy.decks.admin');
});

Route::get('/checkShadowBan/{twitterAccount}', function ($twitterAccount) {
    $scraper = new \App\utils\ScrapingTool('https://api.shadowban.io/api/v1/twitter/@' . $twitterAccount);
    $response = $scraper->makeRequest();
    print_r(json_decode($response)->content);
})->middleware('auth')->name('checkShadowBan');

Route::get('decks/{id}/historial', 'DeckController@historial')->middleware('auth')->name('historial');
Route::get('decks/{id}/inspector/{unico}', 'DeckController@inspector')->middleware('auth')->name('inspector');
Route::get('decks/{id}/rate', 'twitter\TwitterController@limite')->middleware('auth')->name('limite');

Route::view('mantenimiento-view', 'mantenimiento')->name('mantenimiento-view');


Route::get('/puede', 'twitter\TwitterController@unrt')->middleware('auth')->name('puede');

Route::get('/alquiler/{username}', 'DeckController@consentido')->middleware('auth');
Route::get('/alquiler-borrar/{username}', 'DeckController@consentidoBorrar')->middleware('auth');
Route::get('/ver-alquiler', 'DeckController@verArquiler')->middleware('auth');


Route::get('/twitter', 'twitter\TwitterController@index')->middleware('auth')->name('twitter');
Route::get('/callback', 'twitter\TwitterController@callback')->middleware('auth');
Route::post('/RT', 'twitter\TwitterController@darRT')->name('rt')->middleware('auth');


Route::post('/master', 'twitter\TwitterController@master')->name('master')->middleware('auth');

Route::get('/testrt', 'twitter\TwitterController@testrt')->name('testrt')->middleware('auth');


Route::post('/generar', 'twitter\TwitterController@generar')->name('generar')->middleware('auth');
Route::post('/generar1', 'twitter\TwitterController@generar1')->name('generar1')->middleware('auth');
Route::post('/generar3', 'twitter\TwitterController@generar3')->name('generar3')->middleware('auth');

Route::get('/reautorizar', 'twitter\TwitterController@reautorizar')->name('reautorizar')->middleware('auth');


Route::get('/config-cache', 'DeckController@cache');

// Activar / Desactivar Deck
Route::post('/systemStatus', 'DeckController@updateOrCreateSystemStatus')->middleware('auth', 'isOwner')->name('updateOrCreateSystemStatus');

//Actualizar followers de los decks
Route::get('/actualizarDecks', 'DeckController@getDecksFollowers');


Route::get('/agregar', 'PermisosController@crear')->middleware('auth');

Auth::routes();

Route::get('/hora', 'twitter\TwitterController@hora');
