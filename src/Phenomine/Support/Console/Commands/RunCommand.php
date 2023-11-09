<?php

namespace Phenomine\Support\Console\Commands;

use Phenomine\Contracts\Command\Command;

class RunCommand extends Command {
    protected $name = 'run';
    protected $description = 'Run the application on the PHP development server';
    protected $options = [
        'i|host=127.0.0.1' => 'The host address to serve the application on',
        'p|port=8000' => 'The port to serve the application on',
    ];
    protected $arguments = [];

    public function handle() {
        $host = $this->option('host');
        $port = $this->option('port');
        $server = sprintf('%s:%s', $host, $port);
        $command = sprintf('%s -S %s -t public', PHP_BINARY, $server);
        $this->info('Phenomine Development Server');
        $this->line('Press Ctrl-C to quit.');
        $this->newLine();
        exec($command);
        return true;
    }
}
