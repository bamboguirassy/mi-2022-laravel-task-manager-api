<?php

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
    return $request->user();
});

Route::post('user', function(Request $request) {
    $validators = Validator::make($request->all(),[
        'name'=>'required',
        'email'=>'email|unique:users,email',
        'password'=>'confirmed|min:8'
    ]);
    if($validators->fails()) {
        return $validators->errors();
    }
    // créer le user
    $user = new User($request->all());
    $user->password = Hash::make($request->password);

    $user->save();

    return $user;
});

Route::post('login', function(Request $request) {
    $validators = Validator::make($request->all(),[
        'email'=>'required|email|exists:users,email',
        'password'=>'required|min:8'
    ]);
    if($validators->fails()) {
        return $validators->errors();
    }
    $user = User::firstWhere('email',$request->email);
    if(Auth::attempt($request->all())) {
      $token =  $user->createToken('task');
      return $token->plainTextToken;
    }

});
