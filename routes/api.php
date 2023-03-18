<?php

use App\Helper\CustomResponse;
use App\Http\Controllers\TacheController;
use App\Models\Tache;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return CustomResponse::buildCorrectResponse($request->user()->loadCount('taches', 'tacheTerminees', 'tacheEnCours'));
});

Route::post('user', function (Request $request) {
    $validators = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'email|unique:users,email',
        'password' => 'confirmed|min:8'
    ]);
    if ($validators->fails()) {
        return CustomResponse::buildValidationResponse($validators);
    }
    // créer le user
    try {
        //code...
        $user = new User($request->all());
        $user->password = Hash::make($request->password);

        $user->save();
    } catch (\Throwable $th) {
        //throw $th;
        return CustomResponse::buildExceptionResponse($th);
    }

    return CustomResponse::buildCorrectResponse($user);
});

Route::post('login', function (Request $request) {
    $validators = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'password' => 'required|min:8'
    ]);

    if ($validators->fails()) {
        return CustomResponse::buildValidationResponse($validators);
    }
    $user = User::firstWhere('email', $request->email);
    if (Auth::attempt($request->all())) {
        $token =  $user->createToken('task');
        return CustomResponse::buildCorrectResponse($token->plainTextToken);
    }
    return CustomResponse::buildCustomErrorResponse("Auth échouée !");
});


Route::get('tache/{tache:uid}', [TacheController::class, 'show'])->middleware('auth:sanctum');
Route::put('tache/{tache:uid}', [TacheController::class, 'update'])->middleware('auth:sanctum');
Route::delete('tache/{tache:uid}', [TacheController::class, 'destroy'])->middleware('auth:sanctum');
Route::resource('tache', TacheController::class, [
    'only' => ['index', 'store']
])->middleware('auth:sanctum');

Route::get('generate-random-tache/{nombre}', function ($nombre) {
    Tache::factory($nombre)->create();
})->middleware('auth:sanctum');
