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
			'purchase_id' => $user->purchase_id,
			'product_id' => $user->product_id,
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

		// Validar que llegan los datos esperados
		$validated = $request->validate([
			'purchaseId' => 'required|string',
			'productId' => 'required|string',
		]);

		// Guardar los campos
		$user->update([
			'has_paid_no_ads' => true,
			'no_ads_purchased_at' => now(),
			'purchase_id' => $validated['purchaseId'],
			'product_id' => $validated['productId'],
		]);

		return response()->json([
			'message' => 'Compra registrada correctamente',
			'ads_removed' => 1
		]);
	}


}
