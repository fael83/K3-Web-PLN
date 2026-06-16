<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TeamK3Controller extends Controller
{
    public function tim()
    {
        $team = DB::table('k3_team')
            ->where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('public.tim', compact('team'));
    }
}