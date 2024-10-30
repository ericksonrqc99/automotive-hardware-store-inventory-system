<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/pruebas', function () {
    $pdf = PDF::loadView('invoice.invoice-layout');
    return $pdf->download('invoice.pdf');
});
