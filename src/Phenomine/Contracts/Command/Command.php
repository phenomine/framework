<?php

namespace Phenomine\Contracts\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends SymfonyCommand
{
    use InteractsWithIO, Parser;

    protected $name;
    protected $description;
    protected $options = [];
    protected $arguments = [];

    
    public function __construct()
    {
        parent::__construct($this->name);
        
        $this->configure();
    }

    protected function configure()
    {
        $this->setName($this->name);
        $this->setDescription($this->description);

        $this->parseOptionArgs();
    }

    protected function parseOptionArgs() {
        foreach ($this->arguments as $argument => $description) {
            $this->getDefinition()->addArgument(static::parseArgument($argument, $description));
        }

        foreach ($this->options as $option => $description) {
            $this->getDefinition()->addOption(static::parseOption($option, $description));
        }
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $method = method_exists($this, 'handle') ? 'handle' : '__invoke';

        return (int) app()->call([$this, $method]);
    }

    public function getSymfonyCommandInstance() {
        
    }
}
