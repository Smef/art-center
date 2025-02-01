<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class PrivateFileController extends Controller
{
    public function show($path)
    {
        if (! Storage::exists($path)) {
            abort(404);
        }

        return Storage::response($path);
    }
}
