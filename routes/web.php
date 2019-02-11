<?php

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

Auth::routes();

Route::redirect('/', '/tickets/create');

Route::resource('/tickets','TicketController');
Route::get('/tickets/auto-update', 'TicketController@autoUpdate')->name('tickets.auto-update');

Route::middleware(['auth'])->group(function () {
    Route::get('/statistics', 'StatisticsController@index')->name('statistics.index');
    Route::get('/statistics/show', 'StatisticsController@show')->name('statistics.show');

    Route::post('/ticket/{ticket}', 'TicketController@comment')->name('ticket.comment');
    Route::post('/ticket/{ticket}/process', 'TicketController@process')->name('ticket.process');
    Route::post('/ticket/{ticket}/close', 'TicketController@close')->name('ticket.close');
    Route::get('/ticket/{ticket}/attachment', 'TicketController@attachment')->name('ticket.attachment');
    Route::get('/search/ticket', 'TicketController@search')->name('ticket.search');

    Route::resource('ticketCategories', 'TicketCategoriesController')->middleware('can:access-categories');
});
