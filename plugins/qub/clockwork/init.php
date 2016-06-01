<?php

App::before(function() {
    Event::fire('clockwork.controller.start');
});

App::after(function() {
    Event::fire('clockwork.controller.end');
});

Backend\Classes\BackendController::extend(function($controller) {
    $controller->middleware('Clockwork\Support\Laravel\ClockworkMiddleware');
});

Cms\Classes\CmsController::extend(function($controller) {
    $controller->middleware('Clockwork\Support\Laravel\ClockworkMiddleware');
});