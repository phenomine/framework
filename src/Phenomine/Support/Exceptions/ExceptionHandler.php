<?php

namespace Phenomine\Support\Exceptions;

use Phenomine\Support\View;

class ExceptionHandler {

    function errorHandler(int $errNo, string $errMsg, string $file, int $line) {
        echo "$errMsg in $file on line $line";
    }

    public function exceptionHandler($exception) {

        if (env('APP_ENV') == 'production') {
            abort(500);
            exit;
        }

        $file = $exception->getFile();
        $root = $_SERVER['DOCUMENT_ROOT'];
        $root = str_replace('/public', '', $root);
        $root = str_replace('\\public', '', $root);
        // remove the root path from the file path
        $file = str_replace($root, '', $file);
        $data = [
            'type' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $file,
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
            'traceAsString' => $exception->getTraceAsString()
        ];
        ob_end_clean();
        $view = new View(__DIR__.'/../../views');
        $view->render('exception_page.index', ['exception' => $data]);
        exit;
    }
}
