<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactDealController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\ContactController;
use App\Models\Deal;
use App\Models\Contact;
use App\Models\ContactDeal;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::delete('/contact-deal/{id}', [ContactDealController::class, 'destroy']);
Route::get('/', [ContactDealController::class, 'index'])->name('contactDeal.index');
Route::post('contact-deal', [ContactDealController::class, 'store']);

Route::resource('deals', DealController::class);
Route::resource('contacts', ContactController::class);

// техническкя страница - вывести просто таблицы бд
Route::get('/database', function() {
    $deals = Deal::all();
    $contacts = Contact::all();
    $contactDeals = ContactDeal::all();
    return view('database', compact('deals', 'contacts', 'contactDeals'));
})->name('database');
