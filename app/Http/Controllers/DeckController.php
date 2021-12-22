<?php

namespace App\Http\Controllers;

use App\Mantenimiento;
use App\Deck;
use App\Decks_user;
use App\Noticia;
use App\Rt;
use App\User;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class DeckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Auth::user()->hasRole(['Owner'])) {
            $decks = Deck::orderBy('numero','desc')->get();
        } else {
            $deckk = Decks_user::where('username', Auth::user()->username)->get();
            $decks = array();
            $contador = 0;
            foreach ($deckk as $nombre) {

                array_push($decks, Deck::where('nombre', str_replace('_', ' ', $nombre->nombredeck))->first());
            }
        }

        return view('panel.deck.decks', compact('decks'));
    }

    public function disableDeck($deck)
    {

        if (
            !(Auth::user()->hasRole(['admin-' . $deck])
                or  Auth::user()->hasRole(['Owner']))
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
                or  Auth::user()->hasRole(['Owner']))
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|unique:decks',
            'admin' => 'required',
            'descipcion' => 'required',

        ]);

        if (!Auth::user()->hasRole(['Owner'])) {
            return back()->with('error', 'Ya esta parchado =)!!! .i.');
        }

        $usertemp = User::where('username', $request->input('admin'))->first();
        if ($usertemp != null) {
            $registro = new Deck;
            $registro->nombre = $request->input('nombre');
            $registro->admin = $request->input('admin');
            $registro->descripcion = $request->input('descipcion');
            $registro->rt = $request->input('rt');
            $registro->save();
            //crear los permisos
            $role = Role::create(['name' => str_replace(' ', '_', $request->input('nombre'))]);
            $role = Role::create(['name' => 'admin-' . str_replace(' ', '_', $request->input('nombre'))]);

            $usertemp->assignRole("Admin");
            $usertemp->assignRole('admin-' . str_replace(' ', '_', $request->input('nombre')));

            return back();
        } else {
            return back()->withErrors('¡Cuidado! ese nombre de usuario no existe.');
        }
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
    public function noticias()
    {
        $noticias = Noticia::latest()->get();
        return view('panel.deck.noticias', compact('noticias'));
    }

    public function noticiasCrear(Request $request)
    {
        $noticias = new Noticia;
        $noticias->titulo = $request->input('titulo');
        $noticias->descripcion = $request->input('descripcion');
        $noticias->img = $request->input('img');
        $noticias->save();
        return back();
    }

    public function show($id)
    {

        $decks = Decks_user::where('nombredeck', $id)->orderBy('followers', 'desc')->get();

        if (Auth::user()->hasRole(['Owner', $id, 'admin-' . $id])) {
            $contador = 0;
            foreach ($decks as $v) {
                $contador += $v->followers;
            }
            session(['nombredeck' => $id]); //guardamos
            $cred = Deck::where('nombre', str_replace('_', ' ', $id))->first();

            // Check if deck is disabled or not
            if (!$cred->enabled && !Auth::user()->hasRole(['Owner', 'admin-' . $id])) return view('panel.deck.mantenimiento');
            $alv = User::role('admin-' . $id)->get();
            $vipss = User::role('consentido')->get();
            $admins = [];
            foreach ($alv as $aux) {
                $admins[] = $aux->username;
            }
            $vips = [];
            foreach ($vipss as $vip) {
                $vips[] = $vip->username;
            }
            return view('panel.deck.deck', compact('decks', 'id', 'cred', 'contador', 'admins', 'vips'));
        } else {
            abort(403);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { //$decks= DB::table('decks')->first()->where('nombre',str_replace('_',' ',$id));

        if (
            !(Auth::user()->hasRole(['admin-' . $id])
                or  Auth::user()->hasRole(['Owner']))
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
                or  Auth::user()->hasRole(['Owner']))
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
                or  Auth::user()->hasRole(['Owner']))
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (
            !(Auth::user()->hasRole(['admin-' . $id])
                or  Auth::user()->hasRole(['Owner']))
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
                or  Auth::user()->hasRole(['Owner']))
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

        $exitCode = Artisan::call('config:clear');
        $exitCode = Artisan::call('route:cache');
        //$exitCode = Artisan::call('view:clear');
        //  $exitCode = Artisan::call('cache:clear');
        // $exitCode = Artisan::call('migrate:rollback --step=1');

        // $exitCode = Artisan::call('migrate');


        return '<h1>Clear Config cleared</h1>';
    }

    public function mantenimiento($estado, $rango = 'Owner')
    {

        if (Auth::user()->hasRole('Owner')) {

            $asdf = Mantenimiento::first();
            $asdf->estado = $estado;
            $asdf->rango = $rango;
            $asdf->save();
            return 'Estado: ' . $estado . '. Disponible para: ' . $rango;
        } else {
            return 'Good try campeon :*';
        }
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
