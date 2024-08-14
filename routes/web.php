<?php

use Illuminate\Support\Facades\Route;


Route::get('/streaming', 'App\Http\Controllers\WebrtcStreamingController@index');
Route::get('/streaming/{streamId}', 'App\Http\Controllers\WebrtcStreamingController@consumer');
Route::post('/stream-offer', 'App\Http\Controllers\WebrtcStreamingController@makeStreamOffer');
Route::post('/stream-answer', 'App\Http\Controllers\WebrtcStreamingController@makeStreamAnswer');
