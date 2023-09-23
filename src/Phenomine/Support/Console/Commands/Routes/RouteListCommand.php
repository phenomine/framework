<?php

namespace Phenomine\Support\Console\Commands\Routes;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteListCommand extends Command
{
    protected $name = 'route:list ';
    protected $description = 'List all registered routes';

    protected function configure()
    {
        $this
            ->setName($this->name)
            ->setDescription($this->description);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        app()->loadRoutes();
        global $_routes;

        $output->writeln([
            '',
            '<fg=green;options=bold;>Registered Routes</>',
            '',
        ]);

        $output->writeln([
            '<fg=yellow;options=bold;>Method</>',
            '<fg=yellow;options=bold;>URI</>',
            '<fg=yellow;options=bold;>Action</>',
            '',
        ]);

        foreach ($_routes as $route) {
            $output->writeln([
                '<fg=green;options=bold;>'.$route->method.'</>',
                '<fg=green;options=bold;>'.$route->uri.'</>',
                '',
            ]);
        }

        return Command::SUCCESS;
    }
}
