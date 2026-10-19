<?php

use App\Http\Controllers\PublicSite\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::view('/zavod', 'public.about')->name('about');
Route::view('/propozice', 'public.regulations')->name('regulations');
Route::view('/vysledky', 'public.results')->name('results');
Route::view('/prihlasky', 'public.registration')->name('registration');
Route::view('/kontakt', 'public.contact')->name('contact');
