<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class ScheduleContronller extends Controller
{
    public function show(): View {
        return view('pages.schedule');
    }
}
