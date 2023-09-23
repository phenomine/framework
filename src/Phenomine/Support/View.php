<?php

namespace Phenomine\Support;

use Phenomine\Contracts\View\ViewContract;
use Phenomine\Support\Exceptions\ViewException;

class View extends ViewContract
{
    public function render($view, $data = [])
    {
        $view = File::findFilesFromString(base_path($this->basePath), $view, '.latte.php');

        if (!$view) {
            throw new ViewException('View not found');
        }

        // render to output
        $this->latte->render($view, $data);
    }

    public static function exist($view)
    {
        $view = File::findFilesFromString(base_path('res/views'), $view, '.latte.php');

        if (!$view) {
            return false;
        }

        return true;
    }
}
