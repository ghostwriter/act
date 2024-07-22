<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\EventDispatcher\Event\GitHub;

use Ghostwriter\Compliance\EventDispatcher\Event\GitHubEventInterface;
use Override;

/**
 * @implements GitHubEventInterface<bool>
 */
final readonly class GitHubPullRequestReviewEvent implements GitHubEventInterface
{
    public function __construct(
        private string $content
    ) {
    }

    #[Override]
    public function payload(): string
    {
        return $this->content;
    }
}
