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


/* --------------------------  | COMIENZAN TODAS LAS RUTAS RELACIONADAS CON NOTICIAS | ------------------------------ */

Route::get('/', 'NewsController@index')->middleware('feedDeck')->name('news.index');
Route::post('/news', 'NewsController@store')->middleware('feedDeck', 'isOwner')->name('news.store');
Route::delete('/news/{id}', 'NewsController@destroy')->middleware('feedDeck', 'isOwner')->name('news.delete');

/* --------------------------  | COMIENZAN TODAS LAS RUTAS RELACIONADAS CON CATALOGOS | -------------------------------- */

//CatalogAPi
//Owner, configurar el catalog API
Route::post('catalogApi', 'CatalogApiController@store')->middleware(['feedDeck'])->name('catalogApi.store');

//Catalogo de decks
Route::get('decks/catalog', 'DeckController@catalogIndex')->middleware(['feedDeck'])->name('decks.catalog');

//Generar URL de auth
Route::post('decks/catalog/authorize', 'DeckJoinRequestController@buildAuthorizeURL')->middleware(['feedDeck'])->name('decks.catalog.authorize');
//Store deck join request
Route::get('callback/catalog', 'DeckJoinRequestController@callback')->middleware(['feedDeck'])->name('decks.catalog.callback');
Route::delete('deckJoinRequests/{deckJoinRequest}', 'DeckJoinRequestController@destroy')->middleware(['feedDeck'])->name('deckJoinRequests.destroy');
/* --------------------------  | COMIENZAN TODAS LAS RUTAS RELACIONADAS CON DECKS | -------------------------------- */

//Resource deck, crear, editar, actualizar, borrar deck
Route::resource('decks', 'DeckController')->middleware(['feedDeck']);

/* ---------------USUARIOS DEL DECK--------------- */

/* Añadir usuario al deck */
Route::post('decks/{deckId}/users', 'DeckController@newUser')->middleware('feedDeck')->name('decks.users.store');
/* Borrar usuario del deck */
Route::delete('decks/{deckId}/user/{userId}', 'DeckController@deleteUser')->middleware('feedDeck')->name('decks.users.delete');

/* ---------------APIS DEL DECK--------------- */

/* Crear api deck */
Route::post('decks/{deckId}/apis', 'DeckController@storeApi')->middleware('feedDeck')->name('decks.apis.store');
/* Actualizar api */
Route::patch('decks/{deckId}/apis/{apiId}', 'DeckController@updateApi')->middleware('feedDeck')->name('decks.apis.patch');
/* Borrar api deck */
Route::delete('decks/{deckId}/apis/{apiId}', 'DeckController@deleteApi')->middleware('feedDeck')->name('decks.apis.delete');

/* ---------------APIS - Twitter Acoounts --------------- */

/* Ver estado actual de las apis(user) */
Route::get('decks/{deckId}/apis', 'DeckController@verifyUserApis')->middleware('feedDeck')->name('decks.apis.verify');
/* Re/autorizar una api */
Route::post('apis/authorize', 'twitter\TwitterController@buildAuthorizeURL')->middleware('feedDeck')->name('decks.apis.authorize');
/* Hacer RT */
Route::post('/makeRT', 'twitter\TwitterController@makeRT')->middleware('feedDeck')->name('makeRT');

/* Hacer borrar Tweets */
Route::get('/deleteTweets', 'twitter\TwitterController@unrt')->middleware('feedDeck')->name('puede');

/* Master RT */
Route::post('/masterRT', 'twitter\TwitterController@masterRT')->name('masterRT')->middleware('feedDeck', 'isOwner');
Route::post('/userMasterRT', 'twitter\TwitterController@userMasterRT')->name('userMasterRT')->middleware('feedDeck');

/* Recibir callback de twitter Tweets */
Route::get('/callback', 'twitter\TwitterController@callback')->middleware('feedDeck');

/* --------------- Historial del Deck --------------- */

/* Ver estado actual de las apis(user) */
Route::get('decks/{deckId}/records', 'DeckController@getRecords')->middleware('feedDeck')->name('decks.records');
Route::get('decks/{deckId}/records/{recordId}', 'DeckController@showRecord')->middleware('feedDeck')->name('decks.records.show');

/* --------------- Configuracion global  --------------- */

Route::post('/system', 'SystemController@store')->middleware('feedDeck', 'isOwner')->name('system.store');


/*Route::get('/checkShadowBan/{twitterAccount}', function ($twitterAccount) {
    $scraper = new \App\utils\ScrapingTool('https://api.shadowban.io/api/v1/twitter/@' . $twitterAccount);
    $response = $scraper->makeRequest();
    print_r(json_decode($response)->content);
})->middleware('feedDeck')->name('checkShadowBan');*/

Route::view('mantenimiento-view', 'mantenimiento')->name('mantenimiento-view');

Route::get('/config-cache', 'DeckController@cache');


//Target accounts

Route::get('/authorizeApi', function () {
    return redirect('https://www.feed-help.de/auth_api/api.php');
})->name('captureAccount');

Auth::routes();




