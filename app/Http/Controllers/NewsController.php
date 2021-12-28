<?php

namespace App\Http\Controllers;

use App\News;
use App\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{

    public function index()
    {
        $news = DB::table('news')->latest()->get();
        $system = System::first();
        if(!$system){
            $system = (object)[
                'status' => 'enabled'
            ];
        }
        return view('vuexy.news.index', compact('news','system'));
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
