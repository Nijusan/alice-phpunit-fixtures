<?php

if (isset($_ENV['DATABASE_URL'])) {

    passthru(sprintf(
        'php "%s/../bin/console" doctrine:database:drop --force --if-exists ',
        __DIR__
    ));

    passthru(sprintf(
        'php "%s/../bin/console" doctrine:database:create ',
        __DIR__
    ));

    passthru(sprintf(
        'php "%s/../bin/console" doctrine:schema:create ',
        __DIR__
    ));
}

require __DIR__ . '/../vendor/autoload.php';