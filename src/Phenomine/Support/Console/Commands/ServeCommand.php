<?php

namespace Phenomine\Support\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ServeCommand extends Command
{
    protected $name = 'serve ';
    protected $description = 'Serve the application on the PHP development server';

    protected function configure()
    {
        $this
            ->setName($this->name)
            ->setDescription($this->description)
            ->setDefinition(
                new InputDefinition([
                    new InputOption(
                        'host',
                        null,
                        InputOption::VALUE_OPTIONAL,
                        'The host address to serve the application on',
                        'localhost'
                    ),

                    new InputOption(
                        'port',
                        null,
                        InputOption::VALUE_OPTIONAL,
                        'The port to serve the application on',
                        8000
                    ),
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $input->getOption('host').':'.$input->getOption('port');

        // validate host
        if (!filter_var('http://'.$host, FILTER_VALIDATE_URL)) {
            $output->writeln([
                '',
                '<error>Invalid host address</error>',
                '<fg=yellow;>'.$host.' is not a valid host address. Phenomine Development Server exited unexpectedly.</>',
            ]);

            return Command::FAILURE;
        }

        $command = sprintf('%s -S %s -t public', PHP_BINARY, $host);
        $output->writeln([
            '',
            '<fg=green;options=bold;>Starting Phenomine Development Server</>',
            '<fg=green;>Listening on http://'.$host.'</>',
        ]);
        exec($command);
    }
}
