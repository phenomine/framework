<?php

namespace Phenomine\Support\Console\Commands\View;

use Phenomine\Contracts\Command\Command;
use Phenomine\Support\File;


class ClearViewCache extends Command
{
    protected $name = 'view:clear';
    protected $description = 'Clear all cached views';

    public function handle()
    {
        $cachePath = base_path('storage/framework/views');

        if (file_exists($cachePath)) {
            $this->clearCache($cachePath);
        }

        $this->info('Cache cleared successfully');

        return true;
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
