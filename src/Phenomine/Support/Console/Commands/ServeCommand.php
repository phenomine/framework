<?php

namespace Phenomine\Support\Console\Commands;

use Phenomine\Contracts\Command\Command;

class ServeCommand extends Command
{
    protected $name = 'serve ';
    protected $description = 'Serve the application on the PHP development server';

    protected function configure()
    {
        $this->apply();
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
