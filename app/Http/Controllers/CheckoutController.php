<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout.index');
    }
    
    public function process(Request $request)
    {
        // Logique de paiement
        return redirect()->route('checkout.success');
    }
    
    public function success()
    {
        return view('checkout.success');
    }
}