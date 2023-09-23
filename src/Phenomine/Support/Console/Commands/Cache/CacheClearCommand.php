<?php

namespace Phenomine\Support\Console\Commands\Cache;

use Phenomine\Support\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheClearCommand extends Command
{
    protected $name = 'cache:clear ';
    protected $description = 'Clear all cached files';

    protected function configure()
    {
        $this
            ->setName($this->name)
            ->setDescription($this->description);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cachePath = base_path('storage/cache');

        if (file_exists($cachePath)) {
            $this->clearCache($cachePath);
        }

        $output->writeln([
            '',
            '<info>Cache cleared successfully</info>',
            '',
        ]);

        return Command::SUCCESS;
    }

    private function clearCache($path) {
        $files = File::allFiles($path, true);
        foreach ($files as $file) {
            if (File::isFile($file)) {
                unlink($file);
            }
        }
    }
}
