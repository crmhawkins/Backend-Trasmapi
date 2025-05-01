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


        $user->disableAds();

        return response()->json([
            'message' => 'Compra registrada correctamente',
            'ads_removed' => 1
        ]);
    }

}
