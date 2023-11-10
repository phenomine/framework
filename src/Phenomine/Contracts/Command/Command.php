<?php

namespace Phenomine\Contracts\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends SymfonyCommand
{
    use Parser;
    use InteractsWithIO;

    protected $name;
    protected $description;
    protected $options = [];
    protected $arguments = [];

    public function __construct()
    {
        parent::__construct($this->name);
    }

    protected function configure()
    {
        $this->setName($this->name);
        $this->setDescription($this->description);
        $this->parseOptionArgs();
    }

    protected function parseOptionArgs()
    {
        $inputDefinitions = [];
        foreach ($this->arguments as $argument => $description) {
            $inputDefinitions[] = static::parseArgument($argument, $description);
        }
        foreach ($this->options as $option => $description) {
            $inputDefinitions[] = static::parseOption($option, $description);
        }
        $this->setDefinition(new InputDefinition($inputDefinitions));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output instanceof OutputStyle ? $output : app()->make(
            OutputStyle::class,
            ['input' => $input, 'output' => $output]
        );

        $method = method_exists($this, 'handle') ? 'handle' : '__invoke';

        return (int) app()->call([$this, $method]);
    }

    public function getSymfonyCommandInstance()
    {
        return $this;
    }
//
//    public function getInput()
//    {
//        return $this->input;
//    }
//
//    public function getOutput()
//    {
//        return $this->output;
//    }
//
//    public function setOutput(OutputStyle $output)
//    {
//        $this->output = $output;
//    }
}
