<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(): View {
        return view('pages.payment');
    }
}
