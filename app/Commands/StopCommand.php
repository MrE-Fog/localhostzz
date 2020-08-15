<?php

namespace App\Commands;

use App\Brew;
use App\Hosts;
use App\Nginx;
use App\Php;
use LaravelZero\Framework\Commands\Command;

class StopCommand extends Command
{
    protected $signature = 'stop';

    protected $description = 'Shuts down the system.';

    public function handle(Hosts $hosts, Nginx $nginx, Php $php)
    {
        $hosts->clearHosts();

        $this->stopServices();

        $nginx->deleteSiteConfigs();

        $php->deleteConfigs();
        $php->deleteFpmConfigs();
    }

    protected function stopServices()
    {
        $this->line(app(Brew::class)->stopService('mailhog'));
        $this->line(app(Brew::class)->stopService('mariadb'));
        $this->line(app(Brew::class)->stopService('nginx'));
        foreach (config('php.versions') as $version) {
            $this->line(app(Brew::class)->stopService('php@' . $version));
        }
        $this->line(app(Brew::class)->stopService('redis'));
    }
}
