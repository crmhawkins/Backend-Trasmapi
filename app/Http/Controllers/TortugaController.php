<?php

namespace App\Http\Controllers;

use App\Models\Tortuga;
use Illuminate\Http\Request;

class TortugaController extends Controller
{
    public function index(Request $request)
    {
        $query = Tortuga::query();

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $posidonias = $query->latest()->paginate(20);
        $totalPuntuacion = $query->sum('puntuacion');

        return view('tortuga.index', compact('posidonias', 'totalPuntuacion'));
    }
}
