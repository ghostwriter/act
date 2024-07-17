<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Factory;

use Ghostwriter\Compliance\Automation;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\FactoryInterface;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Override;
use RuntimeException;
use Throwable;

use const DIRECTORY_SEPARATOR;

use function is_file;
use function sprintf;

/**
 * @implements FactoryInterface<Automation>
 */
final readonly class AutomationFactory implements FactoryInterface
{
    private const string AUTOMATION_FILE = <<<CODE
        <?php

        declare(strict_types=1);

        use Ghostwriter\Compliance\Automation;
        use Ghostwriter\Compliance\Enum\ComposerStrategy;
        use Ghostwriter\Compliance\Enum\OperatingSystem;
        use Ghostwriter\Compliance\Enum\PhpVersion;
        use Ghostwriter\Compliance\Enum\Tool;

        return Automation::new()
            ->composerStrategies(...ComposerStrategy::cases())
            ->operatingSystems(...OperatingSystem::cases())
            ->phpVersions(...PhpVersion::cases())
            ->tools(...Tool::cases());

        CODE;

    public function __construct(
        private FilesystemInterface $filesystem,
    ) {
    }

    /**
     * @throws Throwable
     */
    #[Override]
    public function __invoke(ContainerInterface $container): Automation
    {
        $currentWorkingDirectory = $this->filesystem->currentWorkingDirectory();

        $automationFile = $currentWorkingDirectory . DIRECTORY_SEPARATOR . 'automation.php';

        if (! is_file($automationFile)) {

            $this->filesystem->createFile($automationFile, self::AUTOMATION_FILE);
        }

        /** @var Automation $automation */
        $automation = require $automationFile;
        if (! $automation instanceof Automation) {
            throw new RuntimeException(
                sprintf('File "%s" must return an instance of %s', $automationFile, Automation::class)
            );
        }

        return $automation;
    }
}
