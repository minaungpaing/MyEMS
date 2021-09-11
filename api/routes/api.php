<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login','Api\AuthController@login');
Route::post('/logout','Api\AuthController@logout')->middleware('auth:api');

Route::prefix('user')->middleware('auth:api')->group(function () {
    Route::post('edit-category', function () {
        return response()->json([
            'message' => 'Admin access',
        ], 200);
    })->middleware('scope:is_admin');

    Route::post('create-category', function () {
        return response()->json([
            'message' => 'Everyone access',
        ], 200);
    })->middleware('scopes:is_admin,is_user');
});
