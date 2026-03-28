<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EcommerceController extends Controller
{
    public function index()
    {
        return view('ecommerce.index', [
            'user' => Auth::user()
        ]);
    }
}
