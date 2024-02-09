<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MessageController;
use App\Models\Menu;
use App\Models\Booking;
use App\Models\Chef;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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

Route::get('/', function () {
    $menu = Menu::get()->take(6);
    $chef = Chef::get()->take(3);

    return view('dashboard', compact('menu','chef'));
});

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'register_proses'])->name('register');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login_proses'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin', [AuthController::class, 'admin'])->name('admin');

Route::resource('/menu', MenuController::class);
Route::resource('/chef', ChefController::class);

Route::post('/booking', [BookingController::class, 'booking'])->name('booking');
Route::get('/booking_dashboard', [BookingController::class, 'booking_dashboard'])->name('booking_dashboard');
Route::get('/booking_proses/{id}', [BookingController::class, 'booking_proses'])->name('booking_proses');

Route::post('/message_proc', [MessageController::class, 'message_proc'])->name('message_proc');
Route::get('/message', [MessageController::class, 'message'])->name('message');

Route::get('/report', function () {
    $data = DB::table('bookings')->where('konfirmasi', 1)->get();

    $pdf = Pdf::loadView('pdf.pdf', ['data' => $data])->setOptions(['defaultFont' => 'sans-serif']);
    return $pdf->stream('Report Booking.pdf');

})->name('report');