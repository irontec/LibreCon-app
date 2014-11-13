<?php

namespace App;

use Silex\Application;

class ServicesLoader
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bindServicesIntoContainer()
    {
        $this->app['assistants.service'] = $this->app->share(function () {
            return new Services\AssistantsService($this->app["db"]);
        });

        $this->app['auth.service'] = $this->app->share(function () {
            return new Services\AuthService($this->app["db"]);
        });

        $this->app['expositors.service'] = $this->app->share(function () {
            return new Services\ExpositorsService($this->app["db"]);
        });

        $this->app['meetings.service'] = $this->app->share(function () {
            return new Services\MeetingsService($this->app["db"]);
        });

        $this->app['photocall.service'] = $this->app->share(function () {
            return new Services\PhotocallService($this->app['photocall']);
        });

        $this->app['schedules.service'] = $this->app->share(function () {
            return new Services\SchedulesService($this->app["db"]);
        });

        $this->app['sponsors.service'] = $this->app->share(function () {
            return new Services\SponsorsService($this->app["db"]);
        });

        $this->app['txokos.service'] = $this->app->share(function () {
            return new Services\TxokosService($this->app["db"]);
        });

        $this->app['version.service'] = $this->app->share(function () {
            return new Services\VersionService($this->app["db"]);
        });
    }
}
