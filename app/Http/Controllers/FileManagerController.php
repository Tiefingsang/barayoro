<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileManagerController extends Controller
{
    public function index()
    {
        return view('files.index', [
            'user' => Auth::user()
        ]);
    }
}
