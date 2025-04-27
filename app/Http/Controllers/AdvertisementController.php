<?php

namespace App\Http\Controllers;

use App\Models\AdSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdvertisementController extends Controller
{
    /**
     * Obtiene la configuración de anuncios para el usuario actual
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserAdStatus()
    {
        $user = Auth::user();

        return response()->json([
            'has_paid_no_ads' => $user->has_paid_no_ads,
            'show_ads' => $user->shouldShowAds(),
        ]);
    }

    /**
     * Registra una compra para eliminar anuncios
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerAdPurchase(Request $request)
    {
        $user = Auth::user();

        // Verificar si ya ha pagado
        if ($user->has_paid_no_ads) {
            return response()->json([
                'message' => 'El usuario ya ha pagado para eliminar anuncios',
                'show_ads' => false
            ]);
        }

        // Aquí iría la lógica de verificación del pago con Google Play / Apple Store
        // Por ahora, simplemente marcamos como pagado

        $user->disableAds();

        return response()->json([
            'message' => 'Compra registrada correctamente',
            'show_ads' => false
        ]);
    }

}
