<?php

namespace Phenomine\Support\Console\Commands\View;

use Phenomine\Support\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearViewCacheCommand extends Command
{
    protected $name = 'view:clear ';
    protected $description = 'Clear all cached views';

    protected function configure()
    {
        $this
            ->setName($this->name)
            ->setDescription($this->description);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cachePath = base_path('storage/framework/views');

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

    private function clearCache($path)
    {
        $files = File::allFiles($path, true);
        foreach ($files as $file) {
            if (File::isFile($file)) {

                if (File::getName($file) == '.gitignore') {
                    continue;
                }
                
                unlink($file);
            }
        }
    }
}
