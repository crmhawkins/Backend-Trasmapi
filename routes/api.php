<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AnuncioController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureEmailIsVerifiedJson;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Notifications\CustomVerifyEmail;
use Laravel\Socialite\Facades\Socialite;



Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/anuncio', [AnuncioController::class, 'mostrar']);
Route::post('/save/{type}', [DataController::class, 'saveData']);
Route::get('/data/{type}', [DataController::class, 'getData']);

Route::get('/auth/google/redirect', fn() => Socialite::driver('google')->stateless()->redirect());
Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = User::firstOrCreate(
        ['email' => $googleUser->getEmail()],
        ['name' => $googleUser->getName(), 'email_verified_at' => now()]
    );

    $token = $user->createToken('auth_token')->plainTextToken;

    // Incluir si ha pagado los anuncios en la URL
    $hasPaidNoAds = $user->has_paid_no_ads ? '1' : '0';

    return redirect("trasmapiapp://auth-callback?token=$token&ads_removed=$hasPaidNoAds");
});

Route::middleware('auth:sanctum')->post('/resend-verification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return response()->json(['message' => 'El correo ya ha sido verificado.'], 200);
    }

    $request->user()->notify(new CustomVerifyEmail());

    return response()->json(['message' => 'Correo de verificación personalizado reenviado.'], 200);
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // Marca el email como verificado
    return response()->json(['message' => 'Correo verificado con éxito']);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/ads/purchase', [AdvertisementController::class, 'registerAdPurchase']);
    Route::get('/ads/status', [AdvertisementController::class, 'getUserAdStatus']);


});
