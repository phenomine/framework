<?php

namespace Phenomine\Support;

class View
{
    public static function render($view, $data = [])
    {
        $sections = [];
        $file = base_path().'/views/'.$view.'.php';

        // Check if @extends is exist
        $extended = false;
        $extends = file_get_contents($file);
        $pattern = '/@extends\(\'(.*)\'\)/';
        preg_match($pattern, $extends, $matches);
        if (count($matches) > 0) {
            $extended = true;
            $file = base_path().'/views/'.$matches[1].'.php';
        }

        // Check if @section is exist on view
        if ($extended) {
            $view_file = base_path().'/views/'.$view.'.php';
        } else {
            $view_file = $file;
        }

        // scan @section from top to bottom line by line
        $lines = file($view_file);

        $currentSection = '';
        foreach ($lines as $line) {
            $pattern = '/@section\(\'(.*)\'\)/';
            $endsection_pattern = '/@endsection/';
            preg_match($pattern, $line, $startSectionMatches);
            preg_match($endsection_pattern, $line, $endSectionMatches);
            if (count($endSectionMatches) > 0) {
                $currentSection = '';
            }
            if ($currentSection != '') {
                $sections[$currentSection] .= $line;
            }
            if (count($startSectionMatches) > 0) {
                $currentSection = $startSectionMatches[1];
                $sections[$currentSection] = '';
            }
        }

        // scan @yield from top to bottom line by line
        $lines = file($file);

        $render = '';
        foreach ($lines as $line) {
            $pattern = '/@yield\(\'(.*)\'\)/';
            preg_match($pattern, $line, $matches);
            if (count($matches) > 0) {
                $render .= $sections[$matches[1]];
            } else {
                $render .= $line;
            }
        }

        if (file_exists($file)) {
            extract($data);
            include_once $file;
        } else {
            echo 'View not found';
        }
    }
}
