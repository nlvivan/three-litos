<?php

use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('test', function () {
    $recipients = auth()->user();

    \Filament\Notifications\Notification::make()
        ->title('Magic Sarap is in low stock')
        ->warning()
        ->icon('heroicon-o-exclamation-circle')
        ->actions([
            Action::make('markAsUnread')
                ->button()
                ->markAsUnread(),
        ])
        ->sendToDatabase($recipients);

    dd('done testing');
})->middleware('auth');
