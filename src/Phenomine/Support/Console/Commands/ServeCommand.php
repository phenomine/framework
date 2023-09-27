<?php

namespace Phenomine\Support\Console\Commands;

use Phenomine\Contracts\Command\Command;

class ServeCommand extends Command
{
    protected $name = 'serve ';
    protected $description = 'Serve the application on the PHP development server';
    protected $options = [
        '--host?' => 'The host address to serve the application on',
        '--port?' => 'The port to serve the application on',
    ];
    protected $arguments = [];

    protected function handle()
    {
        $host = $this->option('host', 'localhost');
        $port = $this->option('port', 8000);

        $server = sprintf('http://%s:%s', $host, $port);

        // validate server
        if (!filter_var('http://'.$host, FILTER_VALIDATE_URL)) {
            $this->newLine();
            $this->error('Invalid host address');
            $this->line($host.' is not a valid host address. Phenomine Development Server exited unexpectedly.');
            return false;
        }

        $command = sprintf('%s -S %s -t public', PHP_BINARY, $server);

        $this->newLine();
        $this->info('Phenomine Development Server started');
        $this->line('Listening on '.$server);
        
        exec($command);
        return true;
    }
}
