<?php

namespace App\Http\Controllers;

use App\Deck;
use App\Decks_user;
use App\Mantenimiento;
use App\Rt;
use App\System;
use App\User;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DeckController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        if ($user->isOwner()) {
            $decks = Deck::orderBy('followers', 'desc')->get();
            $users = DB::table('users')->select(['name', 'id'])->get();
            return view('vuexy.decks.index', compact('decks', 'users'));

        }

        $decks = $user->decks;
        return view('vuexy.decks.index', compact('decks'));

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
        $o = \unserialize($histo->quienes);
        return view('panel.deck.inspector', compact('o', 'id', 'h'));
    }


    public function store(Request $request)
    {
        if (!Auth::user()->isOwner()) {
            abort(403);
        }
        $validatedData = $request->validate([
            'icon' => 'required',
            'name' => 'required|unique:decks',
            'admin_id' => 'required|integer',
            'description' => 'required',
            'rt_number' => 'required|numeric',
            'delete_minutes' => 'required|numeric',
        ]);

        $deckAdmin = User::find($request->input('admin_id'));
        if ($deckAdmin !== null) {
            $deck = Deck::create([
                'name' => $request->input('name'),
                'icon' => $request->input('icon'),
                'owner_name' => $deckAdmin->name,
                'rt_number' => $request->input('rt_number'),
                'delete_minutes' => $request->input('delete_minutes'),
                'description' => $request->input('description'),
                'followers' => 0,
                'enabled' => 1
            ]);

            //Assign permissions
            $deckAdmin->decks()->attach($deck->id, ['role' => 'owner']);

            return back()->withErrors('Deck creado exitosamente');
        }

        return back()->withErrors('¡Cuidado! ese nombre de usuario no existe.');
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
        //Get the actual user
        $user = auth()->user();
        //Lets check if the user belongs to this deck.
        $deckInfo = $user->getDeckInfo($id);
        //If not belongs to it, abort the request.
        if ($deckInfo['hasPermission'] === false) {
            abort(403, 'No tienes permiso para acceder a este Deck. comunicate
            con el administrador si crees que se trata de un error');
        }
        //The user has permission to access the deck, lets get its role.
        $userRole = $deckInfo['role'];

        //Get all the deck information.
        $deck = Deck::where('id', '=', $id)->with(['twitterAccounts', 'twitterAccounts.user'])->get()->sortBy('twitter_accounts.followers');
        if ($deck === null) {
            abort(404);
        }

        return view('panel.deck.deck', compact('deck'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { //$decks= DB::table('decks')->first()->where('nombre',str_replace('_',' ',$id));

        if (
        !(Auth::user()->hasRole(['admin-' . $id])
            or Auth::user()->hasRole(['Owner']))
        ) {
            return back()->with('error', 'Ya esta parchado =)!!! .i.');
        }
        $deck = Deck::where('nombre', str_replace('_', ' ', $id))->first();

        $deck->crearkey = $request->input('key1');
        $deck->crearsecret = $request->input('secret1');
        $deck->borrarkey = $request->input('key2');
        $deck->borrarsecret = $request->input('secret2');
        if ($request->input('key3') != "" && $request->input('secret3') != "") {
            $deck->api3key = $request->input('key3');
            $deck->api3secret = $request->input('secret3');
        }
        $deck->whatsapp = $request->input('whatsapp');

        $deck->save();
        return back();
    }

    public function newUser(Request $request, $id)
    {
        if (
        !(Auth::user()->hasRole(['admin-' . $id])
            or Auth::user()->hasRole(['Owner']))
        ) {
            return back()->with('error', 'Ya esta parchado =)!!! .i.');
        }

        $user = User::where('username', $request->input("username"))->first();
        if (!($user == null)) {

            $registro = new Decks_user;
            $registro->nombredeck = $id;
            $registro->username = $request->input("username");
            $registro->img = "";
            $registro->save();

            $user->assignRole($id);
            return back();
        } else {
            return back()->with('error', '¡Cuidado! ese usuario no está registrado');
        }
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
     * @return \Illuminate\Http\Response
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

    public function eliminarUser(Request $request)
    {
        if (
        !(Auth::user()->hasRole(['admin-' . $request->input("deck-name")])
            or Auth::user()->hasRole(['Owner']))
        ) {
            return back()->with('error', 'Ya esta parchado =)!!! .i.');
        }

        $registro = Decks_user::where([['username', $request->input("username")], ['nombredeck', $request->input("deck-name")]])->first();
        $dale = $request->input("user-id");

        $registro->delete();
        $user = User::where('username', $request->input("username"))->first();
        $user->removeRole($request->input("deck-name"));

        return back()->with('mensaje', 'Usuario con Twitter ' . $dale . ' eliminado exitosamente');
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
}
