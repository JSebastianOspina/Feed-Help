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
Route::get('decks/{id}/historial', 'DeckController@historial')->middleware('auth','mantenimiento')->name('historial');
Route::get('decks/{id}/inspector/{unico}', 'DeckController@inspector')->middleware('auth','mantenimiento')->name('inspector');
Route::get('decks/{id}/rate', 'twitter\TwitterController@limite')->middleware('auth','mantenimiento')->name('limite');

Route::get('/', 'DeckController@news')->middleware('auth','mantenimiento');
Route::get('/asdf', 'DeckController@noticias')->middleware('mantenimiento');
Route::view('mantenimiento-view', 'mantenimiento')->name('mantenimiento-view');

Route::post('/', 'DeckController@noticiasCrear')->middleware('auth','mantenimiento')->name('noticias');

Route::get('/puede', 'twitter\TwitterController@unrt')->middleware('auth','mantenimiento')->name('puede');

Route::get('/alquiler/{username}', 'DeckController@consentido')->middleware('auth','mantenimiento');
Route::get('/alquiler-borrar/{username}', 'DeckController@consentidoBorrar')->middleware('auth','mantenimiento');
Route::get('/ver-alquiler', 'DeckController@verArquiler')->middleware('auth','mantenimiento');


Route::resource('decks','DeckController')->middleware('auth','mantenimiento');
Route::post('panel.deck.nuevo/{id}','DeckController@newUser')->name('nuevouser');
Route::post('panel.deck.admin/{id}','DeckController@newAdmin')->name('nuevoadmin');

Route::get('/twitter', 'twitter\TwitterController@index')->middleware('auth','mantenimiento')->name('twitter');
Route::get('/callback', 'twitter\TwitterController@callback')->middleware('auth','mantenimiento');
Route::post('/RT', 'twitter\TwitterController@darRT')->name('rt')->middleware('auth','mantenimiento');


Route::post('/master', 'twitter\TwitterController@master')->name('master')->middleware('auth','mantenimiento');

Route::get('/testrt', 'twitter\TwitterController@testrt')->name('testrt')->middleware('auth','mantenimiento');


Route::post('/generar', 'twitter\TwitterController@generar')->name('generar')->middleware('auth','mantenimiento');
Route::post('/generar1', 'twitter\TwitterController@generar1')->name('generar1')->middleware('auth','mantenimiento');
Route::post('/generar3', 'twitter\TwitterController@generar3')->name('generar3')->middleware('auth','mantenimiento');

Route::get('/reautorizar', 'twitter\TwitterController@reautorizar')->name('reautorizar')->middleware('auth','mantenimiento');


Route::post('panel.deck.eliminar-user/', 'DeckController@eliminarUser')->name('eliminar-user')->middleware('auth','mantenimiento');


Route::get('/config-cache', 'DeckController@cache');
Route::get('/perros123/{estado}/{rango?}', 'DeckController@mantenimiento')->middleware('auth','mantenimiento')->name('mantenimiento');

// Activar / Desactivar Deck
Route::get('/enableDeck/{deck}', 'DeckController@enableDeck');
Route::get('/disableDeck/{deck}', 'DeckController@disableDeck');

//Actualizar followers de los decks
Route::get('/actualizarDecks', 'DeckController@getDecksFollowers');


Route::get('/agregar', 'PermisosController@crear')->middleware('auth','mantenimiento');

Auth::routes();

Route::get('/home', 'DeckController@noticias')->name('home')->middleware('auth','mantenimiento');
Route::get('/hora', 'twitter\TwitterController@hora');
