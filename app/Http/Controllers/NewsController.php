<?php

namespace App\Http\Controllers;

use App\Deck;
use App\News;
use App\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{

    public function index()
    {
        $news = DB::table('news')->latest()->get();
        if (!(auth()->user()->isOwner())) {
            return view('vuexy.news.index', compact('news'));
        }
        //We are dealing with the system owner
        $system = System::first();
        if (!$system) {
            $system = (object)[
                'status' => 'enabled',
                'same_tweet_id_minutes' => 15,
            ];
        }
        $decks = Deck::all();
        return view('vuexy.news.index', compact('news', 'system','decks'));


    }

    public function store(Request $request)
    {
        News::create($request->all());
        return redirect()->back();
    }

    public function destroy($id)
    {
        News::find($id)->delete();
        return redirect()->route('news.index');
    }
}
