<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posidonia;
use App\Models\Tortuga;
use App\Models\Limpieza;

class DataController extends Controller
{
    public function getData(Request $request, $type)
    {
        switch ($type) {
            case 'posidonias':
                $data = Posidonia::all();
                break;
            case 'tortugas':
                $data = Tortuga::all();
                break;
            case 'limpieza':
                $data = Limpieza::all();
                break;
            default:
                return response()->json(['message' => 'Tipo no válido'], 400);
        }
        return response()->json($data);
    }

    public function saveData(Request $request, $type)
    {
        $request->validate([
            'nombre' => 'required|string',
            'puntuacion' => 'required|integer'
        ]);

        switch ($type) {
            case 'posidonias':
                $data = Posidonia::create($request->all());
                break;
            case 'tortugas':
                $data = Tortuga::create($request->all());
                break;
            case 'limpieza':
                $data = Limpieza::create($request->all());
                break;
            default:
                return response()->json(['message' => 'Tipo no válido'], 400);
        }
        return response()->json(['message' => 'Datos guardados con éxito', 'data' => $data], 201);
    }
}
