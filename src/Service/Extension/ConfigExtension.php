<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Extension;

use Ghostwriter\Compliance\Value\EnvironmentVariables;
use Ghostwriter\Config\Config;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ExtensionInterface;
use Override;

use function chdir;
use function error;
use function error_get_last;
use function sprintf;

/**
 * @implements ExtensionInterface<Config>
 */
final readonly class ConfigExtension implements ExtensionInterface
{
    public function __construct(
        private EnvironmentVariables $environmentVariables,
    ) {
    }

    /**
     * @param Config $service
     */
    #[Override]
    public function __invoke(ContainerInterface $container, object $service): Config
    {
        $currentWorkingDirectory = $this->environmentVariables->get('GITHUB_WORKSPACE');

        $result = chdir($currentWorkingDirectory);
        if ($result === false) {
            error(
                sprintf(
                    'Unable to change current working directory; %s; "%s" given.',
                    error_get_last()['message'] ?? 'No such file or directory',
                    $currentWorkingDirectory
                ),
                __FILE__,
                __LINE__
            );
        }

        // $service->set(Compliance::CURRENT_WORKING_DIRECTORY, $currentWorkingDirectory);

        // $complianceWorkflowTemplate = $currentWorkingDirectory . DIRECTORY_SEPARATOR . 'src/automation.yml.dist';
        // if (file_exists($complianceWorkflowTemplate)) {
        //     $service->set(Compliance::WORKFLOW_TEMPLATE, realpath($complianceWorkflowTemplate));
        // }

        return $service;
    }
}
