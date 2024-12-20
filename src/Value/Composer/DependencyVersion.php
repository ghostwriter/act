<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

use InvalidArgumentException;
use JsonSerializable;
use Override;
use Stringable;

use function mb_trim;

final readonly class DependencyVersion implements JsonSerializable, Stringable
{
    public function __construct(
        private string $content
    ) {
        if (mb_trim($content) === '') {
            throw new InvalidArgumentException('Version cannot be empty');
        }
    }

    public static function new(string $content): self
    {
        return new self($content);
    }

    #[Override]
    public function __toString(): string
    {
        return $this->content;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [$this->content];
    }
}
