<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

use Symfony\Component\Console\Output\OutputInterface;

use function getenv;
use function sprintf;
use function strtr;

final class GithubActionOutput
{
    /**
     * @see https://github.com/actions/toolkit/blob/457303960f03375db6f033e214b9f90d79c3fe5c/packages/core/src/command.ts#L80-L85
     */
    public const ESCAPED_DATA = [
        '%' => '%25',
        "\r" => '%0D',
        "\n" => '%0A',
    ];

    /**
     * @see https://github.com/actions/toolkit/blob/457303960f03375db6f033e214b9f90d79c3fe5c/packages/core/src/command.ts#L87-L94
     */
    public const ESCAPED_PROPERTIES = [
        '%' => '%25',
        "\r" => '%0D',
        "\n" => '%0A',
        ':' => '%3A',
        ',' => '%2C',
    ];

    public function __construct(
        private readonly OutputInterface $output
    ) {
    }

    /**
     * Output a debug log using the Github annotations format.
     *
     * @see https://docs.github.com/en/free-pro-team@latest/actions/reference/workflow-commands-for-github-actions#setting-a-debug-message
     */
    public function debug(
        string $message,
        null|string $file = null,
        null|int $line = null,
        null|int $col = null
    ): void {
        $this->log('debug', $message, $file, $line, $col);
    }

    /**
     * Output an error using the Github annotations format.
     *
     * @see https://docs.github.com/en/free-pro-team@latest/actions/reference/workflow-commands-for-github-actions#setting-an-error-message
     */
    public function error(
        string $message,
        null|string $file = null,
        null|int $line = null,
        null|int $col = null
    ): void {
        $this->log('error', $message, $file, $line, $col);
    }

    /**
     * Output a warning using the Github annotations format.
     *
     * @see https://docs.github.com/en/free-pro-team@latest/actions/reference/workflow-commands-for-github-actions#setting-a-warning-message
     */
    public function warning(
        string $message,
        null|string $file = null,
        null|int $line = null,
        null|int $col = null
    ): void {
        $this->log('warning', $message, $file, $line, $col);
    }

    private function log(
        string $type,
        string $message,
        null|string $file = null,
        null|int $line = null,
        null|int $col = null
    ): void {
        // Some values must be encoded.
        $message = strtr($message, self::ESCAPED_DATA);

        if (! self::isGithubActionEnvironment()) {
            // output the message solely: not in actions
            $this->output->writeln($message);

            return;
        }

        if (! $file) {
            // No file provided, output the message solely:
            $this->output->writeln(sprintf('::%s::%s', $type, $message));

            return;
        }

        $this->output->writeln(sprintf(
            '::%s file=%s,line=%s,col=%s::%s',
            $type,
            strtr($file, self::ESCAPED_PROPERTIES),
            strtr((string) ($line ?? 1), self::ESCAPED_PROPERTIES),
            strtr((string) ($col ?? 0), self::ESCAPED_PROPERTIES),
            $message
        ));
    }

    public static function isGithubActionEnvironment(): bool
    {
        return getenv('GITHUB_ACTIONS') !== false;
    }
}
