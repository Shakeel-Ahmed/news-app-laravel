<?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Login;
    use App\Http\Controllers\News;

    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider and all of them will
    | be assigned to the "api" middleware group. Make something great!
    |
    */

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });


    Route::post('verify', [Login::class, 'Verify']);
    Route::post('register', [Login::class, 'RegisterUser']);
    Route::get('news/{source?}', [News::class, 'Index']);
