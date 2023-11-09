<?php

namespace Phenomine\Support\Console\Commands\Route;

use Phenomine\Contracts\Command\Command;
use Phenomine\Support\Route;

class RouteListCommand extends Command {
    protected $name = 'route:list';
    protected $description = 'List all registered routes';
    protected $options = [
        'm|method=?' => 'Filter the routes by method',
        'f|full=?' => 'Show full origin'
    ];

    public function handle() {
        $routes = Route::getRoutes();
        $data = [];
        foreach ($routes as $route) {
            $action = '';
            // check if route is a closure
            if (is_callable($route->handler)) {
                $action = 'Closure';
            }

            // check if route is a controller
            if (is_string($route->handler)) {
                $action = $route->handler;
            }

            // check if route is a controller method
            if (is_array($route->handler)) {
                $action = $route->handler[0].'@'.$route->handler[1];
            }

            if ($this->option('method') != '?' && $this->option('method') != null) {
                if ($route->method != $this->option('method')) {
                    continue;
                }
            }

            if ($this->option('full') != '?') {
                $origin = $route->origin;
            } else {
                $origin = $route->origin ? '...' . substr($route->origin, -30) : 'Unknown';
            }

            $data[] = [
                $route->method,
                $route->uri,
                $route->name,
                $action,
                $origin
            ];
        }
        $this->table(['Method', 'URI', 'Name', 'Action', 'Route File'], $data);
        return true;
    }
}
