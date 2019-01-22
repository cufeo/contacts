<?php

/*
|--------------------------------------------------------------------------
| Single Page Application Route
|--------------------------------------------------------------------------
| Routing is handled by the single page application (VueJs)
| Ajax requests are routed in api.php
|
*/

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');