<?php

namespace Phenomine\Support;

use Phenomine\Contracts\View\ViewContract;
use Phenomine\Support\Exceptions\ViewException;
use Throwable;

class View extends ViewContract
{
    public function render($view, $data = [])
    {
        $view = File::findFilesFromString($this->basePath, $view, '.latte.php');

        if (!$view) {
            throw new ViewException('View not found');
        }

        try {
            $this->latte->render($view, $data);
        } catch (Throwable $e) {
            throw new ViewException($e->getMessage());
        }
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
