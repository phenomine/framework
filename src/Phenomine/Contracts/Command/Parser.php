<?php

namespace Phenomine\Contracts\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

trait Parser
{
    protected static function parseArgument($argument, $description)
    {
        switch (true) {
            case str_ends_with($argument, '?*'):
                return new InputArgument(trim($argument, '?*'), InputArgument::IS_ARRAY, $description);
            case str_ends_with($argument, '*'):
                return new InputArgument(trim($argument, '*'), InputArgument::IS_ARRAY | InputArgument::REQUIRED, $description);
            case str_ends_with($argument, '?'):
                return new InputArgument(trim($argument, '?'), InputArgument::OPTIONAL, $description);
            case preg_match('/(.+)\=\*(.+)/', $argument, $matches):
                return new InputArgument($matches[1], InputArgument::IS_ARRAY, $description, preg_split('/,\s?/', $matches[2]));
            case preg_match('/(.+)\=(.+)/', $argument, $matches):
                return new InputArgument($matches[1], InputArgument::OPTIONAL, $description, $matches[2]);
            default:
                return new InputArgument($argument, InputArgument::REQUIRED, $description);
        }
    }

    protected static function parseOption($option, $description)
    {
        $matches = preg_split('/\s*\|\s*/', $option, 2);

        $shortcut = null;

        if (isset($matches[1])) {
            $shortcut = $matches[0];
            $option = $matches[1];
        }

        switch (true) {
            case str_ends_with($option, '='):
                return new InputOption(trim($option, '='), $shortcut, InputOption::VALUE_OPTIONAL, $description);
            case str_ends_with($option, '=*'):
                return new InputOption(trim($option, '=*'), $shortcut, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, $description);
            case preg_match('/(.+)\=\*(.+)/', $option, $matches):
                return new InputOption($matches[1], $shortcut, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, $description, preg_split('/,\s?/', $matches[2]));
            case preg_match('/(.+)\=(.+)/', $option, $matches):
                return new InputOption($matches[1], $shortcut, InputOption::VALUE_OPTIONAL, $description, $matches[2]);
            default:
                return new InputOption($option, $shortcut, InputOption::VALUE_NONE, $description);
        }
    }
}
