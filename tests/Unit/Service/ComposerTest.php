<?php

declare(strict_types=1);

namespace Tests\Unit\Service;

use Ghostwriter\Compliance\Container\ServiceProvider;
use Ghostwriter\Compliance\Value\Composer\Composer;
use Ghostwriter\Compliance\Value\Composer\Json\ComposerJsonReader;
use Ghostwriter\Compliance\Value\Composer\Lock\ComposerLockReader;
use Ghostwriter\Compliance\Value\EnvironmentVariables;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Tests\Unit\AbstractTestCase;
use Throwable;

use const DIRECTORY_SEPARATOR;

#[CoversClass(Composer::class)]
#[UsesClass(ComposerJsonReader::class)]
#[UsesClass(ComposerLockReader::class)]
#[UsesClass(EnvironmentVariables::class)]
#[UsesClass(ServiceProvider::class)]
final class ComposerTest extends AbstractTestCase
{
    /**
     * @throws Throwable
     */
    public function testGetJsonFilePath(): void
    {
        $root = \getcwd();

        self::assertSame($this->composer->getJsonFilePath($root), $root . DIRECTORY_SEPARATOR . 'composer.json');
    }

    /**
     * @throws Throwable
     */
    public function testGetLockFilePath(): void
    {
        $root = \getcwd();

        self::assertSame($this->composer->getLockFilePath($root), $root . DIRECTORY_SEPARATOR . 'composer.lock');
    }
}
