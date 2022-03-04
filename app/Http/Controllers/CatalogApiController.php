<?php

namespace App\Http\Controllers;

use App\CatalogApi;
use App\Http\Requests\StoreCatalogApiRequest;

class CatalogApiController extends Controller
{
    public function store(StoreCatalogApiRequest $request)
    {
        CatalogApi::updateOrCreate(['id' => 1], $request->all());
        return redirect()->back()->withSuccess('API actualizada exitosamente');

    }
}
