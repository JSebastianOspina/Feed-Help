<?php

namespace App\Http\Controllers;

use App\Api;
use App\Deck;
use App\DeckUser;
use App\Record;
use App\TwitterAccount;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class DeckController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        if ($user->isOwner()) {
            $decks = Deck::orderBy('followers', 'desc')->get();
        } else {
            $decks = $user->decks;
        }

        return view('vuexy.decks.index', compact('decks'));
    }

    public function catalogIndex()
    {
        $decks = Deck::where('isPublic', true)->get();

        return view('vuexy.decks.catalog.index', compact('decks'));
    }

    public function edit($deckId)
    {
        if (!($this->hasAdminPermissions($deckId))) {
            return redirect()->route('decks.index')->withError('No eres admin de este Deck ¿pero aun así quieres editarlo? 🤡 ');

        }
        $canEditDeck = false;

        //Check if it's owner to allow the user edit the deck
        if ($this->hasOwnerPermissions($deckId)) {
            $canEditDeck = true;
        }
        $deck = Deck::where('id', $deckId)->with(['twitterAccounts', 'apis'])->first();
        if ($deck === null) {
            return redirect()->route('decks.index')->withError('¿Estás bien? parece que estas intentando editar a un Deck que no existe 🤡 ');
        }
        return view('vuexy.decks.admin', compact('deck', 'canEditDeck'));
    }

    private function hasAdminPermissions($deck_id): bool
    {
        $user = auth()->user();
        $deckRole = $user->getDeckInfo($deck_id)['role'];

        //If doesnt have the required role, return 403 error code
        return $deckRole === "owner" || $deckRole === "admin" || $user->isOwner();
    }

    private function hasOwnerPermissions($deck_id): bool
    {
        $user = auth()->user();
        $deckRole = $user->getDeckInfo($deck_id)['role'];

        //If doesnt have the required role, return 403 error code
        return $deckRole === "owner" || $user->isOwner();
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isOwner()) {
            return redirect()->route('decks.index')->withError('Ira we, así quedaste 🤡 🤡 ');
        }
        $validatedData = $request->validate([
            'icon' => 'required',
            'name' => 'required|unique:decks',
            'owner_username' => 'required|string',
            'rt_minutes' => 'required|numeric',
            'delete_minutes' => 'required|numeric',
        ]);

        $deckAdmin = User::where('username', $request->input('owner_username'))->first();
        if ($deckAdmin === null) {
            return back()->withError('El nombre de usuario proporcionado no existe');
        }

        $deck = Deck::create([
            'name' => $request->input('name'),
            'icon' => $request->input('icon'),
            'owner_name' => $deckAdmin->name,
            'rt_minutes' => $request->input('rt_minutes'),
            'delete_minutes' => $request->input('delete_minutes'),
            'followers' => 0,
            'enabled' => 1
        ]);

        //Assign permissions
        $deckAdmin->decks()->attach($deck->id, ['role' => 'owner']);

        return back()->withSuccess('Deck creado exitosamente');
    }

    public function storeApi(Request $request, $deckId)
    {
        //Check if the user can not perform the action
        if (!$this->hasAdminPermissions($deckId)) {
            return redirect()->route('decks.edit', ['deck' => $deckId])
                ->withError('No tienes permisos para realizar esta acción');

        }
        //Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|min:1',
            'key' => 'required|string|min:5',
            'secret' => 'required|string|min:5',
            'type' => 'required|string',
            'deck_id' => [
                'required',
                Rule::in([$deckId])
            ]
        ]);

        $api = Api::create($request->all());

        //Desactivar todas las cuentas del deck
        $twitterAccounts = $api->deck->twitterAccounts;
        foreach ($twitterAccounts as $twitterAccount) {
            $twitterAccount->status = 'pending';
            $twitterAccount->save();
        }

        return redirect()->route('decks.edit', ['deck' => $deckId])
            ->withSuccess('API creada exitosamente');
    }

    public function updateApi(Request $request, $deckId, $apiId)
    {
        //Check if the user can not perform the action
        if (!$this->hasAdminPermissions($deckId)) {

            return redirect()->route('decks.edit', ['deck' => $deckId])
                ->withError('No tienes permisos para realizar esta acción');
        }
        //Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|min:1',
            'key' => 'required|string|min:15',
            'secret' => 'required|string|min:15',
            'type' => 'required|string',

        ]);

        $api = Api::findOrFail($apiId);
        $api->fill($request->all());
        $api->save();


        return redirect()->route('decks.edit', ['deck' => $deckId])
            ->withSuccess('API actualizada exitosamente');
    }

    public function deleteApi($deckId, $apiId)
    {
        //Check if the user can not perform the action
        if (!$this->hasAdminPermissions($deckId)) {
            abort(403);
        }
        //Validate the request
        $api = Api::find($apiId);
        if ($api === null) {
            return redirect()
                ->route('decks.edit', ['deck' => $deckId])
                ->withError('La API que intentas borrar no existe');
        }
        $api->delete();
        return redirect()->route('decks.edit', ['deck' => $deckId])
            ->withSuccess('API borrada exitosamente');
    }


    public function show($id)
    {
        //Check if the Deck exists
        $deck = Deck::find($id);
        if ($deck === null) {
            return redirect()->route('decks.index')->withError('¿Estás bien? parece que estas intentando acceder a un Deck que no existe 🤡');
        }

        if (auth()->user()->canUseDeck($deck) === false) {
            return redirect()->route('decks.index')->withError('Estas intentando acceder a un Deck que se encuentra inactivo. Por favor intenta mas tarde');
        }

        //Lets check if the user belongs to this deck.

        $user = auth()->user();
        $deckInfo = $user->getDeckInfo($id);
        //If not belongs to it, abort the request.
        if ($deckInfo['hasPermission'] === false) {
            return redirect()->route('decks.index')
                ->withError('No tienes permiso para acceder a este Deck. Comunicate con el administrador si crees que se trata de un error');

        }
        //The user has permission, let's continue

        $deckUsers = DB::table('deck_user')
            ->select([
                'deck_user.role as role',
                'u.username as userUsername', 'u.id as userId',
                't.username as twitterUsername', 't.followers as twitterFollowers', 't.status as twitterStatus', 't.image_url',
                'donors.id as isDonor'
            ])
            ->where('deck_user.deck_id', $id)
            ->join('users AS u', 'deck_user.user_id', '=', 'u.id')
            ->leftJoin('twitter_accounts AS t', 'deck_user.twitter_account_id', '=', 't.id')
            ->leftJoin('donors', 'donors.user_id', '=', 'deck_user.user_id')
            ->get()
            ->sortBy('twitterFollowers', SORT_NATURAL, true);

        if ($this->hasAdminPermissions($id)) {
            $hasPermission = true;
            $users = DB::table('users')->select('username', 'id')->get();
            //Load active deck join requests
            $deckJoinRequests = $deck->deckJoinRequests;
            return view('vuexy.decks.show', compact('deck', 'deckUsers', 'users', 'hasPermission', 'deckJoinRequests'));
        }

        $hasPermission = false;
        return view('vuexy.decks.show', compact('deck', 'deckUsers', 'hasPermission'));

    }

    public function update(Request $request, $deckId)
    {
        $requestedData = $request->all();

        if (!$this->hasAdminPermissions($deckId)) {
            return redirect()->route('decks.index')
                ->withError('No puedes editar un deck del cual no eres administrador');
        }

        //Validate the request
        $validatedData = $request->validate([
            'whatsapp_group_url' => 'nullable|string',
            'telegram_username' => 'nullable|string',
            'rt_minutes' => 'required|numeric|min:60',
            'delete_minutes' => 'required|numeric|min:10',
            'min_followers' => 'required|numeric|min:0',
            'enabled' => 'required|boolean',
        ]);

        $deck = Deck::findOrFail($deckId);
        if (array_key_exists('isPublic', $requestedData)) {
            $requestedData['isPublic'] = true;
        } else {
            $requestedData['isPublic'] = false;
        }
        $deck->fill($requestedData);
        $deck->save();
        return redirect()->route('decks.edit', ['deck' => $deckId])
            ->withSuccess('Deck actualizado exitosamente');
    }

    public function newUser(Request $request, $deckId)
    {
        //Check if the user can perform this action
        if (!$this->hasAdminPermissions($deckId)) {
            abort(403);
        }

        //Check if the user has space for more users
        $deck = Deck::findOrFail($deckId);

        $userLimit = $deck->user_limit ?? 20;

        if (count($deck->users) >= $userLimit) {
            return back()->withError('El deck ha alcanzado el máximo límite de usuarios');
        }

        //Check if is asking for a valid role
        Validator::make($request->all(), [
            'user_username' => 'required|string',
            'role' => [
                'required',
                'string',
                Rule::in(['admin', 'user']),
            ],
        ]);

        $newUser = User::where('username', $request->input('user_username'))->first();
        if ($newUser === null) {
            return back()->withError('El usuario que intentas agregar al Deck no existe');
        }
        //Sync pivot table
        $deck_user = DeckUser::where('deck_id', $deckId)
            ->where('user_id', $newUser->id)
            ->first();
        if ($deck_user !== null) {
            $deck_user->role = $request->input('role');
            $deck_user->save();
        } else {
            //Sync pivot table
            DeckUser::create([
                'user_id' => $newUser->id,
                'deck_id' => $deckId,
                'role' => $request->input('role')
            ]);
        }
        return back()->withSuccess('Se ha añadido el usuario y le has asignado un rol de: ' . $request->input('role'));
    }

    public function deleteUser($deckId, $userId)
    {
        if (!$this->hasAdminPermissions($deckId)) {
            abort(403);
        }
        $deckUser = DeckUser::where('user_id', $userId)
            ->where('deck_id', $deckId)
            ->first();
        if (!$deckUser) {
            return back()->withError('El usuario que intentas eliminar no pertenece al Deck');
        }
        //delete twitter account
        $twitterAccount = TwitterAccount::find($deckUser->twitter_account_id);
        if ($twitterAccount) {
            //Remove twitter account followers from deck
            $deck = $twitterAccount->deck;
            $deck->followers -= $twitterAccount->followers;
            $deck->save();
            //Delete the twitter account
            $twitterAccount->delete();
        }

        //delete the deck_user relationship (No twitter account has been attach yet)
        $deckUser->delete();

        return back()->withSuccess('Usuario eliminado exitosamente');
    }


    public function destroy($id)
    {
        if (!$this->hasOwnerPermissions($id)) {
            abort(403, 'No tienes permito realizar esta acción');
        }
        $deck = Deck::where('id', $id)->first();
        if ($deck === null) {
            return redirect()->back()
                ->withError('El Deck que intentas eliminar no existe');
        }
        $deck->delete();
        return redirect()->route('decks.index')->withSuccess('Deck Eliminado exitosamente');
    }


    public function cache()
    {

        $exitCode = Artisan::call('optimize');
        return '<h1>Clear Config cleared</h1>';
    }


    public function getDecksFollowers()
    {
        $decks = Deck::all();
        foreach ($decks as $deck) {

            $deck_users = Decks_user::where('nombredeck', str_replace(' ', '_', $deck->nombre))->get();
            $followers = 0;
            foreach ($deck_users as $deck_user) {
                $account_followers = $deck_user->followers;
                if ($account_followers != null) {
                    $followers = $followers + $account_followers;
                }
            }
            $deck->numero = $followers;
            $deck->save();
        }
        return 'actualizado';
    }


    /*COMIENZA LA LOGICA DE LAS APIS */

    public function verifyUserApis($deckId)
    {
        if (!(auth()->user()->getDeckInfo($deckId)['hasPermission'])) {
            return redirect()->route('decks.index')
                ->withError('Parece que te perdiste, no perteneces al Deck al que querias vincular APIS ');
        }

        $apis = DB::table('apis')
            ->select([
                'id',
                'name',
                'type'
            ])
            ->where('apis.deck_id', '=', $deckId)
            ->get();

        foreach ($apis as $api) {

            $TwitterAccountApi = DB::table('twitter_account_apis')
                ->select([
                    'isActive',
                ])
                ->where('user_id', '=', auth()->user()->id)
                ->where('api_id', '=', $api->id)
                ->first();
            if ($TwitterAccountApi === null) {
                $api->isActive = false;

            } else {

                $api->isActive = $TwitterAccountApi->isActive;
            }
        }
        $rtAndDeleteApis = $this->getRtAndDeleteApis($apis);

        return view('vuexy.decks.apis', [
            'rtApis' => $rtAndDeleteApis['rtApis'],
            'deleteApis' => $rtAndDeleteApis['deleteApis'],
            'deckId' => $deckId
        ]);

    }

    private function getRtAndDeleteApis($allApis)
    {
        $rtApis = [];
        $deleteApis = [];
        foreach ($allApis as $api) {
            if ($api->type === 'rt') {
                $rtApis[] = $api;
            } else {
                $deleteApis[] = $api;
            }
        }
        return ['rtApis' => $rtApis, 'deleteApis' => $deleteApis];
    }

    /* DECK RECORDS */

    public function getRecords($deckId)
    {
        $records = Record::where('deck_id', $deckId)->limit(10)->latest()->get();
        return view('vuexy.decks.records.index', compact('records', 'deckId'));

    }

    public function showRecord($deckId, $recordId)
    {
        $records = Record::find($recordId);
        if ($records === null) {
            return redirect()->back()->withError('No existe el tweet que deseas inspeccionar');
        }
        $details = unserialize($records->extra_info);
        return view('vuexy.decks.records.show', compact('details', 'deckId'));
    }

}
