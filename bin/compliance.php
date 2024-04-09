<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use Throwable;

use function dirname;
use function file_exists;
use function fwrite;
use function sprintf;

use const PHP_EOL;
use const STDERR;

/** @var ?string $_composer_autoload_path */
(static function (string $composerAutoloadPath): void {
    if (! file_exists($composerAutoloadPath)) {
        fwrite(
            STDERR,
            sprintf('[ERROR]Failed to locate "%s"\n please run "composer install"\n', $composerAutoloadPath)
        );

        exit(1);
    }

    require_once $composerAutoloadPath;

    try {
        /** #BlackLivesMatter */
        Compliance::main();
    } catch (Throwable $throwable) {
        fwrite(STDERR, sprintf(
            '[%s] %s%s%s' . PHP_EOL,
            $throwable::class,
            $throwable->getMessage(),
            PHP_EOL,
            $throwable->getTraceAsString(),
        ));

        exit(2);
    }
})($_composer_autoload_path ?? dirname(__DIR__) . '/vendor/autoload.php');
