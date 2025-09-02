<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class ScalarController extends Controller
{
    public function index()
    {
        return view('docs.scalar');
    }

    public function spec()
    {
        return response()->file(base_path('docs/scalar/openapi.json'));
    }
}
