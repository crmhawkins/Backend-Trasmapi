<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posidonia;

class PosidoniaController extends Controller
{
    public function index(Request $request)
    {
        $query = Posidonia::query();

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $posidonias = $query->latest()->paginate(20);
        $totalPuntuacion = $query->sum('puntuacion');

        return view('posidonia.index', compact('posidonias', 'totalPuntuacion'));
    }
}
