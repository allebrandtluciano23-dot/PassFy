<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $eventos = Evento::with(['cidade', 'lotes'])
            ->where('statusEvento', 'A')
            ->where('dataEvento', '>=', Carbon::today())
            ->orderBy('dataEvento', 'asc')
            ->orderBy('horaEvento', 'asc')
            ->limit(8)
            ->get();
        
        return view('home.index', compact('eventos'));
    }
}
