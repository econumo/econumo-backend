<?php
declare(strict_types=1);

namespace App\Command\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class MakeApiCommand extends Command
{
    protected static $defaultName = 'make:api';

    protected function configure()
    {
        $this
            ->setDescription('Генерация структуры API')
            ->addArgument('url', InputArgument::OPTIONAL, '/api/v1/organization-events/get-list')
            ->addOption('http-method', null, InputOption::VALUE_REQUIRED, 'HTTP метод, например GET', 'GET')
            ->addOption('dry-run', null, InputOption::VALUE_NONE);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dryRun = $input->getOption('dry-run');
        $httpMethod = strtoupper(trim($input->getOption('http-method')));
        if (!\in_array($httpMethod, ['GET', 'POST'])) {
            throw new \RuntimeException('Only GET and POST allowed');
        }
        $url = strtolower(trim($input->getArgument('url')));
        if (!preg_match('!^/(api)/(v\d+)/([^/]+)/([^/]+)!i', $url, $matches)) {
            throw new \RuntimeException('Метод может быть только GET или POST');
        }

        $structure = [];
        $files = [
            'src/UI/Controller/__CG_URL_TYPE_CC__/__CG_API_VERSION_CC__/__CG_API_SUBJECT_CC__/__CG_API_ACTION_CC__/Controller.php' => "UI/Controller{$httpMethod}.php",
            'src/UI/Controller/__CG_URL_TYPE_CC__/__CG_API_VERSION_CC__/__CG_API_SUBJECT_CC__/__CG_API_ACTION_CC__/Form.php' => 'UI/Form.php',
            'src/UI/Controller/__CG_URL_TYPE_CC__/__CG_API_VERSION_CC__/__CG_API_SUBJECT_CC__/__CG_API_ACTION_CC__/Model.php' => 'UI/Model.php',
            'src/Application/__CG_API_SUBJECT_CC__/__CG_API_SUBJECT_CC__Service.php' => 'Application/Service.php',
            'src/Application/__CG_API_SUBJECT_CC__/Dto/__CG_API_ACTION_CC__DisplayDto.php' => 'Application/Dto.php',
            'src/Application/__CG_API_SUBJECT_CC__/Assembler/__CG_API_ACTION_CC__DisplayAssembler.php' => 'Application/Assembler.php',
        ];
        foreach ($files as $dst => $src) {
            $placeholders = $this->getPlaceholders($dst, $matches, $httpMethod);
            $path = $this->getFilePath($dst, $placeholders);
            $content = $this->getFileContent($src, $placeholders);
            $structure[$path] = $content;
        }

        $filesystem = new Filesystem();
        foreach ($structure as $path => $content) {
            $output->writeln($path);
            if ($dryRun) {
                continue;
            }

            $filesystem->mkdir(dirname($path));
            $filesystem->remove($path);
            $filesystem->touch($path);
            $filesystem->appendToFile($path, $content);
        }

        return 0;
    }

    private function getPlaceholders(string $destination, array $params, string $httpMethod): array
    {
        [$url, $prefix, $version, $subject, $action] = $params;

        return [
            '__CG_URL__' => $url,
            '__CG_HTTP_METHOD__' => $httpMethod,
            '__CG_URL_TYPE__' => $prefix,
            '__CG_URL_TYPE_CC__' => $this->toCamelCase($prefix),
            '__CG_URL_TYPE_CCL__' => $this->toCamelCase($prefix, false),
            '__CG_API_VERSION__' => $version,
            '__CG_API_VERSION_CC__' => $this->toCamelCase($version),
            '__CG_API_VERSION_CCL__' => $this->toCamelCase($version, false),
            '__CG_API_SUBJECT__' => $subject,
            '__CG_API_SUBJECT_CC__' => $this->toCamelCase($subject),
            '__CG_API_SUBJECT_CCL__' => $this->toCamelCase($subject, false),
            '__CG_API_ACTION__' => $action,
            '__CG_API_ACTION_CC__' => $this->toCamelCase($action),
            '__CG_API_ACTION_CCL__' => $this->toCamelCase($action, false),
        ];
    }

    private function toCamelCase(string $value, bool $firstUpper = true): string
    {
        $value = str_replace('-', ' ', $value);
        $value = ucwords(strtolower($value));
        $value = str_replace(' ', '', $value);
        if (!$firstUpper) {
            $value = lcfirst($value);
        }

        return $value;
    }

    private function getFilePath(string $destination, array $placeholders): string
    {
        $basePath = dirname(__DIR__, 3);

        return $basePath.'/'.str_replace(array_keys($placeholders), array_values($placeholders), $destination);
    }

    private function getFileContent(string $source, array $placeholders): string
    {
        $content = file_get_contents(__DIR__.'/../../../config/code-templates/'.$source);

        return str_replace(array_keys($placeholders), array_values($placeholders), $content);
    }
}
