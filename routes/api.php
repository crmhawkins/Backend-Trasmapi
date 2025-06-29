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
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\PersonalAccessToken;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;


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


Route::post('/auth/apple/callback', function (Request $request) {
    $request->validate([
        'apple_id' => 'required',
        'token' => 'required',
    ]);

    $identityToken = $request->input('token');
    $appleId = $request->input('apple_id');
    $email = $request->input('email');
    $name = $request->input('name') ?? 'Usuario Apple';

    try {
        // 1. Obtener claves públicas de Apple
        $applePublicKeys = Http::get('https://appleid.apple.com/auth/keys')->json();
        $decoded = JWT::decode($identityToken, JWK::parseKeySet($applePublicKeys), ['RS256']);

        // 2. Validar audiencia e emisor
        if ($decoded->iss !== 'https://appleid.apple.com') {
            return response()->json(['error' => 'Emisor inválido'], 401);
        }

        if ($decoded->aud !== 'com.hawkins.trasmapi') { // 👈 usa tu bundle ID exacto aquí
            return response()->json(['error' => 'Audiencia inválida'], 401);
        }

        // 3. Coincide con el apple_id recibido
        if ($decoded->sub !== $appleId) {
            return response()->json(['error' => 'Apple ID inválido'], 401);
        }

    } catch (\Exception $e) {
        return response()->json(['error' => 'Token de Apple inválido', 'debug' => $e->getMessage()], 401);
    }

    // 4. Buscar o crear usuario
    $user = User::where('apple_id', $appleId)->first();
    if (!$user) {
        if (!$email) {
            return response()->json(['error' => 'No se recibió email del usuario'], 422);
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'apple_id' => $appleId,
            'email_verified_at' => now(),
            'password' => bcrypt(Str::random(16)), // por si luego quieres login normal
        ]);
    }

    // 5. Token y respuesta
    $token = $user->createToken('auth_token')->plainTextToken;
    $hasPaidNoAds = $user->has_paid_no_ads ? '1' : '0';

    return response()->json([
        'token' => $token,
        'ads_removed' => $hasPaidNoAds,
    ]);
});

Route::middleware('auth:sanctum')->delete('/user/delete', function (Request $request) {
    $user = $request->user();
    $user->delete();

    return response()->json(['message' => 'Cuenta eliminada']);
});
