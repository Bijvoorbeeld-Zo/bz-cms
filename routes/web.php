<?php

use Illuminate\Support\Facades\Route;
use JornBoerema\BzCMS\Models\Page;

foreach (Page::all() as $page) {
    Route::middleware('web')->get($page->slug, config('bz-cms.page_template'));
}
