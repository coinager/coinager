<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\IncomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('accounts', AccountController::class)->only(['index']);
    Route::apiResource('expenses', ExpenseController::class)->only(['store']);
    Route::apiResource('incomes', IncomeController::class)->only(['store']);
});
