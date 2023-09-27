<?php

namespace Phenomine\Contracts\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends SymfonyCommand
{
    use InteractsWithIO;

    protected $name;
    protected $description;
    protected $options = [];
    protected $arguments = [];

    /**
     * Define an option for the command.
     *
     * @param string $name
     * @param string $shortcut
     * @param OptionType|int $optionType
     * @param string $description
     * @param mixed $default
     *
     * @return InputOption|mixed
     */
    protected function defineOption(string $name, string $shortcut = null, int $optionType = null, string $description = '', $default = null)
    {
        $option = new InputOption($name, $shortcut, $optionType, $description, $default);
        $this->options[] = $option;

        return $option;
    }

    /**
     * Define an argument for the command.
     *
     * @param string $name
     * @param int $argumentMode
     * @param string $description
     * @param mixed $default
     *
     * @return InputArgument|mixed
     */
    protected function defineArgument(string $name, int $argumentMode = null, string $description = '', $default = null)
    {
        $argument = new InputArgument($name, $argumentMode, $description, $default);
        $this->arguments[] = $argument;

        return $argument;
    }

    public function __construct()
    {
        $this->setName($this->name);
        $this->setDescription($this->description);
    }

    protected function apply() {
        foreach ($this->options as $option) {
            $this->addOption($option->getName(), $option->getShortcut(), $option->getMode(), $option->getDescription(), $option->getDefault());
        }

        foreach ($this->arguments as $argument) {
            $this->addArgument($argument->getName(), $argument->getMode(), $argument->getDescription(), $argument->getDefault());
        }
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $method = method_exists($this, 'handle') ? 'handle' : '__invoke';

        return (int) $this->call([$this, $method]);
    }
}
