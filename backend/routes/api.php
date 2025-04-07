<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/invoices/{type}', [InvoiceController::class, 'index']);
Route::post('/invoices/{type}', [InvoiceController::class, 'store']);
Route::get('/invoices/{type}/{id}', [InvoiceController::class, 'show']);
Route::put('/invoices/{type}/{id}', [InvoiceController::class, 'update']);
Route::delete('/invoices/{type}/{id}', [InvoiceController::class, 'destroy']);


Route::get('/clients', [ClientController::class, 'index']);
Route::post('/clients', [ClientController::class, 'store']);
Route::get('/clients/{id}', [ClientController::class, 'show']);
Route::put('/clients/{id}', [ClientController::class, 'update']);
Route::delete('/clients/{id}', [ClientController::class, 'destroy']);
