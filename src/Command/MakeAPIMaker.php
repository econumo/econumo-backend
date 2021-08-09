<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

class MakeAPIMaker extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:api';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Генерация структуры файлов для API')
            ->addArgument('method', InputArgument::REQUIRED, 'HTTP метод, GET или POST')
            ->addArgument('url', InputArgument::REQUIRED, '/api/v1/business-english/receive-payment')
            ->addOption(
                'base-path',
                null,
                InputOption::VALUE_REQUIRED,
                'Базовый путь к генерируемым файлам',
                realpath(__DIR__ . '/../../'),
            )
            ->addOption(
                'dry-run',
                null,
                InputOption::VALUE_NONE,
                'Пробный запуск (покажет создаваемые файлы, но ничего создавать не будет)',
            )
            ->addOption('delete', null, InputOption::VALUE_NONE, 'Удалить созданные файлы')
            ->setHelp('');
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $url = strtolower(trim((string) $input->getArgument('url')));
        $httpMethod = strtoupper(trim((string) $input->getArgument('method')));
        $basePath = (string) $input->getOption('base-path');
        $dryRun = (bool) $input->getOption('dry-run');
        $delete = (bool) $input->getOption('delete');

        if (!in_array($httpMethod, ['GET', 'POST'], true)) {
            throw new \RuntimeException('Wrong HTTP method');
        }

        if (
            !preg_match(
                '!^/api/(?P<version>v\d+)/(?P<module>[a-z0-9\-]+)/(?P<action>[a-z0-9]+)-(?P<subject>[a-z0-9]+)$!i',
                $url,
                $matches,
            )
        ) {
            throw new \RuntimeException('Wrong URL');
        }

        $module = implode(
            '',
            array_map(function ($item) {
                return ucfirst(strtolower($item));
            }, explode('-', $matches['module'])),
        );
        $subject = ucfirst(strtolower($matches['subject']));
        $action = ucfirst(strtolower($matches['action']));
        $version = strtoupper($matches['version']);

        $data = [
            '_CG_URL_' => $url,
            '_CG_METHOD_' => $httpMethod,
            '_CG_APPROOT_' => 'App',
        ];
        foreach (
            [
                '_CG_MODULE_' => $module,
                '_CG_SUBJECT_' => $subject,
                '_CG_ACTION_' => $action,
                '_CG_VERSION_' => $version,
            ]
            as $key => $value
        ) {
            $data[$key . 'LCFIRST_'] = lcfirst($value);
            $data[$key . 'LOWER_'] = lcfirst($value);
            $data[$key] = $value;
        }

        $templates = [
            sprintf('Controller%s.php', $httpMethod) => sprintf(
                '%s/src/UI/Controller/Api/%s/%s/%s%s%sController.php',
                $basePath,
                $module,
                $subject,
                $action,
                $subject,
                $version,
            ),
            'Form.php' => sprintf(
                '%s/src/UI/Controller/Api/%s/%s/Validation/%s%s%sForm.php',
                $basePath,
                $module,
                $subject,
                $action,
                $subject,
                $version,
            ),
            'Cest.php' => sprintf(
                '%s/tests/api/%s/%s/%s%s%sCest.php',
                $basePath,
                strtolower($version),
                $module,
                $action,
                $subject,
                $version,
            ),
            'RequestDto.php' => sprintf(
                '%s/src/Application/%s/%s/Dto/%s%s%sRequestDto.php',
                $basePath,
                $module,
                $subject,
                $action,
                $subject,
                $version,
            ),
            'ResultDto.php' => sprintf(
                '%s/src/Application/%s/%s/Dto/%s%s%sResultDto.php',
                $basePath,
                $module,
                $subject,
                $action,
                $subject,
                $version,
            ),
            'ResultAssembler.php' => sprintf(
                '%s/src/Application/%s/%s/Assembler/%s%s%sResultAssembler.php',
                $basePath,
                $module,
                $subject,
                $action,
                $subject,
                $version,
            ),
            'Service.php' => sprintf('%s/src/Application/%s/%s/%sService.php', $basePath, $module, $subject, $subject),
        ];

        $filesystem = new Filesystem();
        foreach ($templates as $template => $path) {
            $io->writeln($path);
            if ($dryRun) {
                continue;
            }
            if ($delete) {
                try {
                    $filesystem->remove($path);
                } catch (\Throwable $exception) {
                    $io->write($exception->getMessage());
                }
                continue;
            }

            clearstatcache();
            $content = $this->getContent($template, $data);
            $dir = dirname($path);
            if (!$filesystem->exists($dir)) {
                $filesystem->mkdir($dir);
            }

            if ($filesystem->exists($path)) {
                $filesystem->remove($path);
            }
            $filesystem->touch($path);
            $filesystem->appendToFile($path, $content);
        }
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        $dependencies->addClassDependency(Command::class, 'console');
    }

    private function getContent(string $file, array $data): string
    {
        $path = __DIR__ . '/../../config/code-templates/Api/' . $file;
        $content = file_get_contents($path);
        return str_replace(array_keys($data), array_values($data), $content);
    }
}