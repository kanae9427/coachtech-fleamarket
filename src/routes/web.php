<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/email/verify', function () {
    return view('auth.verify_email');//認証待機ページ
})->middleware('auth')->name('verification.notice');
Route::post('/email/send-verification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    session()->flash('message', '認証メールを送信しました！'); // ✅ 認証メール送信処理
    return back();
})->middleware(['auth'])->name('verification.send');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); //ここでメール認証を完了
    return redirect('/mypage/profile'); // 認証後プロフィール設定へ
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification(); // 再送信処理
    return back()->with('message', '認証メールを再送しました！');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

Route::get('/', [ItemController::class, 'index'])->name('home');

Route::get('/item/{item}', [ItemController::class, 'show'])->name('item.show');
Route::post('/item/{item}/favorite', [FavoriteController::class, 'toggle'])->name('item.favorite')->middleware('auth');
Route::post('/item/{item}/comment', [CommentController::class, 'store'])->name('item.comment')->middleware('auth');


Route::post('/register', [AuthController::class, 'store'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth')->group(function () {
    // マイページ関連
    Route::get('/mypage', [MypageController::class, 'show'])->name('mypage.show'); // プロフィール表示
    Route::get('/mypage/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/mypage/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

    // 出品関連
    Route::get('/sell', [SellController::class, 'sell'])->name('sell');//出品フォーム表示
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store'); //出品処理

    // 購入関連
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/success/{item_id}', [PurchaseController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/cancel', [PurchaseController::class, 'cancel'])->name('purchase.cancel');

    //配送先変更
    Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])->name('address.edit');
    Route::put('/purchase/address/{item_id}', [AddressController::class, 'update'])->name('address.update');

});