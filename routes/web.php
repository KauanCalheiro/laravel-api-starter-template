<?php

Route::get('/', fn () => view('welcome'));

require __DIR__ . '/web/telescope.php';
