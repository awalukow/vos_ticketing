<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransportasiController;
use App\Http\Controllers\RuteController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Exports\PemesananExport;
use Maatwebsite\Excel\Facades\Excel;


$allowedIps = [
    '36.88.182.218'
];


if (env('APP_ENV') === 'maintenance' && !in_array(Request::ip(), $allowedIps))  {
    // Route only to maintenance view
    Route::get('/', [RegisterController::class, 'showMaintenance'])->name('maintenance');
    Route::get('/{any}', [RegisterController::class, 'showMaintenance'])->where('any', '.*'); // Catch-all
}else {
    Auth::routes();

    // Public Routes
    Route::get('/signup', [RegisterController::class, 'showFastRegistrationForm'])->name('fast-register');
    Route::post('/signup', [RegisterController::class, 'fastRegister'])->name('fast-register');
    Route::get('/adminRegister', [RegisterController::class, 'showAdminRegistrationForm'])->name('admin-register');
    Route::post('/adminRegister', [RegisterController::class, 'fastRegister'])->name('admin-register');
    Route::get('/view/pdf', [RegisterController::class, 'view_pdf']);

    // Password Reset Routes...
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Authenticated Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/pengaturan', [UserController::class, 'create'])->name('pengaturan');
        Route::post('/edit/name', [UserController::class, 'name'])->name('edit.name');
        Route::post('/edit/password', [UserController::class, 'password'])->name('edit.password');
        Route::get('/transaksi/{kode}', [LaporanController::class, 'show'])->name('transaksi.show');
        Route::post('/upload-bukti-pembayaran/{id}', [LaporanController::class, 'uploadBuktiPembayaran'])->name('upload.bukti.pembayaran');
        Route::post('/upload-bukti-pembayarans/{id}', [LaporanController::class, 'uploadBuktiPembayaranFisik'])->name('upload.bukti.pembayaran.fisik');

        // Petugas Routes
        Route::middleware(['petugas'])->group(function () {
            Route::get('/pembayaran/{id}', [LaporanController::class, 'pembayaran'])->name('pembayaran');
            Route::get('/petugas', [LaporanController::class, 'petugas'])->name('petugas');
            Route::post('/petugas', [LaporanController::class, 'kode'])->name('petugas.kode');
            Route::post('/updateCheckIn/{id}', [LaporanController::class, 'updateCheckIn'])->name('laporan.updateCheckIn');

            Route::get('/transaksi', [LaporanController::class, 'index'])->name('transaksi');

            // Admin Routes
            Route::middleware(['admin'])->group(function () {
                Route::get('/home', [HomeController::class, 'index'])->name('home');
                Route::resource('/category', CategoryController::class);
                Route::resource('/transportasi', TransportasiController::class);
                Route::resource('/rute', RuteController::class);
                Route::resource('/user', UserController::class);

                Route::get('/transaksi', [LaporanController::class, 'index'])->name('transaksi');
                Route::get('/transaksi-pending', [LaporanController::class, 'transaksi_pending'])->name('transaksi_pending');
                Route::get('/ticket-gereja', [LaporanController::class, 'ticket_gereja'])->name('ticket_gereja');
                Route::get('/ticket-fisik', [LaporanController::class, 'ticket_fisik'])->name('ticket_fisik');

                Route::get('/order', [PemesananController::class, 'index'])->name('order');
                Route::get('/pesan/{kursi}/{data}/{referral?}', [PemesananController::class, 'pesan'])->name('pesan');
                Route::get('/cari/kursi/{data}', [PemesananController::class, 'edit'])->name('cari.kursi');
                Route::post('/resend-ticket/{id}', [PemesananController::class, 'resendTicketEmail'])->name('resend.ticket.email');
                Route::get('/pemesanan/export', function () {
                                return Excel::download(new PemesananExport, 'pemesanan.xlsx');
                            })->name('pemesanan.export');
            });

            // AdminChurch Routes
            Route::middleware(['adminchurch'])->group(function () {
                Route::get('/ticket-gereja', [LaporanController::class, 'ticket_gereja'])->name('ticket_gereja');
            });

            // SuperAdmin Routes
            Route::middleware(['superadmin'])->group(function () {
                Route::get('/home', [HomeController::class, 'index'])->name('home');
                Route::resource('/category', CategoryController::class);
                Route::resource('/transportasi', TransportasiController::class);
                Route::resource('/rute', RuteController::class);
                Route::resource('/user', UserController::class);

                Route::get('/order', [PemesananController::class, 'index'])->name('order');
                Route::get('/pesan/{kursi}/{data}/{referral?}', [PemesananController::class, 'pesan'])->name('pesan');
                Route::get('/cari/kursi/{data}', [PemesananController::class, 'edit'])->name('cari.kursi');

                Route::get('/transaksi-pending', [LaporanController::class, 'transaksi_pending'])->name('transaksi_pending');
                Route::get('/ticket-gereja', [LaporanController::class, 'ticket_gereja'])->name('ticket_gereja');
                Route::get('/ticket-fisik', [LaporanController::class, 'ticket_fisik'])->name('ticket_fisik');
                Route::patch('/user/{id}/change-password', [UserController::class, 'changePassword'])->name('user.changePassword');
                Route::post('/cancelOrder/{id}', [LaporanController::class, 'cancelOrder'])->name('cancelOrder');
                Route::post('/resend-ticket/{id}', [LaporanController::class, 'resendTicketEmail'])->name('resend.ticket.email');
                Route::get('/pemesanan/export', function () {
                                return Excel::download(new PemesananExport, 'pemesanan.xlsx');
                            })->name('pemesanan.export');
            });
        });

        // Penumpang Routes
        Route::middleware(['penumpang'])->group(function () {
            Route::get('/pesan/{kursi}/{data}/{referral?}', [PemesananController::class, 'pesan'])->name('pesan');
            Route::get('/cari/kursi/{data}', [PemesananController::class, 'edit'])->name('cari.kursi');
            Route::resource('/', PemesananController::class);
            Route::get('/history', [LaporanController::class, 'history'])->name('history');
            Route::get('/encrypt-data', [PemesananController::class, 'encryptData'])->name('encryptData');
        });

        // SuperAdmin also has access to Penumpang views
        Route::middleware(['superadmin'])->group(function () {
            Route::get('/pesan/{kursi}/{data}/{referral?}', [PemesananController::class, 'pesan'])->name('pesan');
            Route::get('/cari/kursi/{data}', [PemesananController::class, 'edit'])->name('cari.kursi');
            Route::get('/history', [LaporanController::class, 'history'])->name('history');
            Route::get('/encrypt-data', [PemesananController::class, 'encryptData'])->name('encryptData');
        });
    });

    // 🔻 IMPORTANT: PLACE THIS ROUTE AT THE VERY END 🔻
    // It must come last to avoid conflicts with more specific routes
    Route::get('/{id}/{data}', [PemesananController::class, 'show'])->name('show');
}