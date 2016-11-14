<?php

use Laravel\Lumen\Application;
use Netpromotion\Profiler\Adapter\PsrLoggerAdapter;
use Netpromotion\Profiler\Adapter\TracyBarAdapter;
use Netpromotion\Profiler\Profiler;
use Netpromotion\TracyPsrLogger\TracyPsrLogger;
use Tracy\Debugger;

require_once __DIR__ . "/../require_me.php";

if (DEBUG_MODE === true) {
    Debugger::enable(true, __DIR__ . "/storage/logs");
    Profiler::enable();
}

tracy_wrap(function() {
    $profileLogger = new PsrLoggerAdapter(new TracyPsrLogger());
    Profiler::start(/* keep default label for better preview */);

    $app = new Application(__DIR__);

    $app->get("/lumen/", function() use ($app) {
        Profiler::start("GET /lumen/");

        doSomething();
        $return = $app->welcome();

        Profiler::finish("GET /lumen/");

        return $return;
    });

    $app->run();

    Profiler::finish(/* keep default label for better preview */);
    $profileLogger->log();
}, [new TracyBarAdapter()]);
