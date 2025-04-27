<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureEmailIsVerifiedJson;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Notifications\CustomVerifyEmail;
use Laravel\Socialite\Facades\Socialite;



Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/auth/google/redirect', fn() => Socialite::driver('google')->stateless()->redirect());
Route::get('/auth/google/callback', function () {
  $googleUser = Socialite::driver('google')->stateless()->user();
  $user = User::firstOrCreate(
    ['email' => $googleUser->getEmail()],
    ['name' => $googleUser->getName(), 'email_verified_at' => now()]
  );
  $token = $user->createToken('mobile')->plainTextToken;
  return redirect("myapp://auth-callback?token=$token");
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

Route::middleware('auth:sanctum',EnsureEmailIsVerifiedJson::class)->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/save/{type}', [DataController::class, 'saveData']);
    Route::get('/data/{type}', [DataController::class, 'getData']);
    Route::post('/ads/purchase', [AdvertisementController::class, 'registerAdPurchase']);
    Route::get('/ads/status', [AdvertisementController::class, 'getUserAdStatus']);


});
