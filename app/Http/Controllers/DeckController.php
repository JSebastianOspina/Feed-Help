<?php

namespace App\Http\Controllers;

use App\Api;
use App\Deck;
use App\Record;
use App\Rt;
use App\System;
use App\TwitterAccount;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use function unserialize;

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

    public function edit($deckId)
    {
        $deck = Deck::where('id', $deckId)->with(['twitterAccounts', 'apis'])->first();
        return view('vuexy.decks.admin', compact('deck'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isOwner()) {
            abort(403);
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
            return back()->withErrors('El nombre de usuario proporcionado no existe');
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

        return back()->withErrors('Deck creado exitosamente');

        return back()->withErrors('¡Cuidado! ese nombre de usuario no existe.');
    }

    public function storeApi(Request $request, $deckId)
    {
        //Check if the user can not perform the action
        $this->hasOwnerPermissions($deckId);
        //Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|min:1',
            'key' => 'required|string|min:15',
            'secret' => 'required|string|min:15',
            'type' => 'required|string',
            'deck_id' => [
                'required',
                Rule::in([$deckId])
            ]
        ]);

        $api = Api::create($request->all());

        return redirect()->route('decks.edit', ['deck' => $deckId]);
    }

    private function hasOwnerPermissions($deck_id): void
    {
        $user = auth()->user();
        $deckRole = $user->getDeckInfo($deck_id)['role'];

        //If doesnt have the required role, return 403 error code
        if (!($deckRole === "owner")) {
            abort('403');
        }
    }

    public function updateApi(Request $request, $deckId, $apiId)
    {
        //Check if the user can not perform the action
        $this->hasOwnerPermissions($deckId);
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


        return redirect()->route('decks.edit', ['deck' => $deckId]);
    }

    public function deleteApi($deckId, $apiId)
    {
        //Check if the user can not perform the action
        $this->hasOwnerPermissions($deckId);
        //Validate the request
        $api = Api::find($apiId);
        if ($api === null) {
            abort(404);
        }
        $api->delete();
        return redirect()->route('decks.edit', ['deck' => $deckId]);
    }

    public function disableDeck($deck)
    {

        if (
        !(Auth::user()->hasRole(['admin-' . $deck])
            or Auth::user()->hasRole(['Owner']))
        ) {
            return back()->with('error', 'Ya esta parchado =)!!! .i.');
        }
        $deck = Deck::where('nombre', str_replace('_', ' ', $deck))->first();
        $deck->enabled = 0;
        $deck->save();
        return back()->with('error', 'Deck desactivado con exito');
    }

    public function enableDeck($deck)
    {
        if (
        !(Auth::user()->hasRole(['admin-' . $deck])
            or Auth::user()->hasRole(['Owner']))
        ) {
            return back()->with('error', 'Ya esta parchado =)!!! .i.');
        }
        $deck = Deck::where('nombre', str_replace('_', ' ', $deck))->first();
        $deck->enabled = 1;
        $deck->save();
        return back()->with('error', 'Deck activado con exito');
    }

    public function historial($id)
    {
        $histo = Rt::where('deck', $id)->orderBy('created_at', 'desc')->limit(10)->get();

        return view('panel.deck.historial', compact('histo', 'id'));
    }

    public function inspector($id, $unico)
    {
        $histo = Rt::find($unico);
        $o = [];
        $h = "1277402467278430208";
        $o = unserialize($histo->quienes);
        return view('panel.deck.inspector', compact('o', 'id', 'h'));
    }

    public function consentido($username)
    {
        if (!Auth::user()->hasRole(['Owner'])) {
            return back()->with('error', 'Ya esta parchado =)!!! .i.');
        }

        $usertemp = User::where('username', $username)->first();
        $usertemp->assignRole("consentido");

        return 'Añadido con exito a el usuario: ' . $username;
    }

    public function consentidoBorrar($username)
    {
        if (!Auth::user()->hasRole(['Owner'])) {
            return back()->with('error', 'Ya esta parchado =)!!! .i.');
        }
        $usertemp = User::where('username', $username)->first();
        $usertemp->removeRole("consentido");
        return 'Eliminado con exito a el usuario: ' . $username;
    }

    public function verArquiler()
    {
        if (Auth::user()->hasRole(['Owner'])) {
            return Role::where('name', 'consentido')->first()->users()->get();
        }
    }

    public function show($id)
    {
        //Check if the Deck exists
        $deck = Deck::find($id);
        if ($deck === null) {
            abort(404);
        }

        //Lets check if the user belongs to this deck.

        $user = auth()->user();
        $deckInfo = $user->getDeckInfo($id);
        //If not belongs to it, abort the request.
        if ($deckInfo['hasPermission'] === false) {
            abort(403, 'No tienes permiso para acceder a este Deck. comunicate
            con el administrador si crees que se trata de un error');
        }
        //The user has permission, let's continue

        $deckUsers = DB::table('deck_user')
            ->select([
                'u.username as userUsername', 'u.id as userId',
                't.username as twitterUsername', 't.followers as twitterFollowers', 't.status as twitterStatus', 't.image_url',
            ])
            ->where('deck_user.deck_id', $id)
            ->join('users AS u', 'deck_user.user_id', '=', 'u.id')
            ->leftJoin('twitter_accounts AS t', 'deck_user.twitter_account_id', '=', 't.id')
            ->get()
            ->sortBy('twitter_accounts.followers');
        //If the user is owner or admin , lets
        $userRole = $deckInfo['role'];
        if ($userRole === 'owner' || $userRole === 'admin') {
            $users = DB::table('users')->select('name', 'id')->get();
            return view('vuexy.decks.show', compact('deck', 'deckUsers', 'users'));

        }
        return view('vuexy.decks.show', compact('deck', 'deckUsers'));

    }

    public function update(Request $request, $deckId)
    {
        $requestedData = $request->all();

        $this->hasOwnerPermissions($deckId);
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
        return redirect()->route('decks.edit', ['deck' => $deckId]);
    }

    public function newUser(Request $request, $deckId)
    {
        //Check if the user can perform this action
        $this->hasAdminPermissions($deckId);
        //Check if is asking for a valid role
        Validator::make($request->all(), [
            'role' => [
                'required',
                'string',
                Rule::in(['admin', 'user']),
            ],
        ]);

        $newUser = User::find($request->input('user_id'));
        $deckRole = $newUser->getDeckInfo($deckId);
        //Check if the user already exist in the deck
        if ($deckRole['hasPermission'] === true) {
            return abort(403, 'El usuario ya pertenece al deck');
        }

        $newUser->decks()->sync([$deckId], ['role' => $request->input('role')]);
        return back();
    }

    private function hasAdminPermissions($deck_id): void
    {
        $user = auth()->user();
        $deckRole = $user->getDeckInfo($deck_id)['role'];

        //If doesnt have the required role, return 403 error code
        if (!($deckRole === "owner" || $deckRole === "admin")) {
            abort('403');
        }
    }

    public function deleteUser($deckId, $userId)
    {
        $this->hasAdminPermissions($deckId);
        $deckUser = DB::table('deck_user')
            ->where('user_id', $userId)
            ->where('deck_id', $deckId)
            ->first();
        if (!$deckUser) {
            abort(404);
        }
        //delete twitter account
        $twitterAccount = TwitterAccount::find($deckUser->twitter_account_id);
        if ($twitterAccount) {
            $twitterAccount->delete();
        }
        //delete deck relationship
        DB::table('deck_user')
            ->where('user_id', $userId)
            ->where('deck_id', $deckId)
            ->delete();

        return back()->with('mensaje', 'Usuario eliminado exitosamente');
    }

    public function newAdmin(Request $request, $id)
    {

        if (
        !(Auth::user()->hasRole(['admin-' . $id])
            or Auth::user()->hasRole(['Owner']))
        ) {
            return back()->with('error', 'Ya esta parchado =)!!! .i.');
        }


        $user = User::where('username', $request->input("username"))->first();
        if (!($user == null)) {
            if ($request->input("accion") == "Añadir") {
                $user->assignRole('admin-' . $id);
                return back();
            } else {
                $user->removeRole('admin-' . $id);
                return back();
            }
        } else {
            return back()->with('error', '¡Cuidado! ese usuario no está registrado');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return ResphasAdminPermissionsonse
     */
    public function destroy($id)
    {
        if (
        !(Auth::user()->hasRole(['admin-' . $id])
            or Auth::user()->hasRole(['Owner']))
        ) {
            return back()->with('error', 'Ya esta parchado =)!!! .i.');
        }

        $borrar = Deck::where('nombre', str_replace('_', ' ', $id))->first();
        $borrar->delete();
        Role::findByName($id)->delete();
        Role::findByName('admin-' . $id)->delete();

        return redirect()->route('decks.index');
    }


    public function cache()
    {

        $exitCode = Artisan::call('optimize');
        return '<h1>Clear Config cleared</h1>';
    }

    public function updateOrCreateSystemStatus(Request $request)
    {
        $statusName = System::getStatusName($request->input('statusId'));
        $system_status = System::first();
        if (!$system_status) {
            $system_status = new System ();
        }
        $system_status->status = $statusName;
        $system_status->save();
        return redirect()->route('news.index');
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
            abort(403);
        }

        $apis = DB::table('apis')
            ->select([
                'apis.id',
                'apis.name',
                'apis.type',
                'ta.isActive',
                'ta.twitter_account_id'
            ])
            ->where('apis.deck_id', $deckId)
            ->leftJoin('twitter_account_apis AS ta', 'apis.id', '=', 'ta.api_id')
            ->where(function ($query) {
                $query->where('ta.user_id', auth()->user()->id)
                    ->orWhere('ta.twitter_account_id', '=', null);
            })
            ->get();

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
        $records = Record::where('deck_id', $deckId)->get();
        return view('vuexy.decks.records.index', compact('records', 'deckId'));

    }

    public function showRecord($deckId, $recordId)
    {
        $records = Record::where('deck_id', $deckId)->latest()->first();
        $details = unserialize($records->extra_info);
        return view('vuexy.decks.records.show', compact('details', 'deckId'));

    }
}
