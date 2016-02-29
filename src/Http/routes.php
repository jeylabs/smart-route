<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::get('{any}', function($url) {
    $definedRouteRedirection = config('smart-route.defined');
    $requestedPath = Request::path();
    if(array_has($definedRouteRedirection, $requestedPath)) {
        return redirect()->to($definedRouteRedirection[$requestedPath]);
    }
    $allRoutes = Route::getRoutes()->getRoutes();
    $words = [];
    $closest = '/';
    foreach($allRoutes as $r) {
        $uriWords = preg_split('/[\s,\/\-]+/', $r->uri());
        $ww = [];
        foreach($uriWords as $w) {
            $ww[$w] = $r->uri();
        }
        $words = array_merge($words, $ww);
    }
    $shortest = -1;
    foreach ($words as $word => $uri) {
        $lev = levenshtein($url, $word);
        if ($lev == 0) {
            $closest = $uri;
            break;
        }
        if ($lev <= $shortest || $shortest < 0) {
            $closest  = $uri;
            $shortest = $lev;
        }
    }
    return redirect($closest);
});