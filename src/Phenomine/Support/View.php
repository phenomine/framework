<?php

namespace Phenomine\Support;

use Latte\Engine;
use Phenomine\Support\Exceptions\ViewException;

class View
{
    public static function render($view, $data = [])
    {

        $view = File::findFilesFromString(base_path('res/views'), $view, '.latte');

        if (!$view) {
            throw new ViewException('View not found');
        }

        $latte = new Engine();
        // cache directory
        $latte->setTempDirectory(storage_path('cache'));

        // render to output
        $latte->render($view, $data);
    }
}
