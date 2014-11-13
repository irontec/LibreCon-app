<?php

namespace App;

use Silex\Application;

class RoutesLoader
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->instantiateControllers();
    }

    private function instantiateControllers()
    {

        $this->app['assistants.controller'] = $this->app->share(function () {
            return new Controllers\AssistantsController(
                $this->app['assistants.service'],
                $this->app['version.service'],
                $this->app['auth.service']
            );
        });

        $this->app['auth.controller'] = $this->app->share(function () {
            return new Controllers\AuthController(
                $this->app['auth.service'],
                array('path' => $this->app['gearmand.path'],'server' => $this->app['gearmand.server'])
            );
        });

        $this->app['expositors.controller'] = $this->app->share(function () {
            return new Controllers\ExpositorsController(
                $this->app['expositors.service'],
                $this->app['version.service'],
                $this->app['auth.service']
            );
        });

        $this->app['link.controller'] = $this->app->share(function () {
            return new Controllers\LinkController(
              $this->app['twig'],
              $this->app['auth.service']
          );
        });

        $this->app['meetings.controller'] = $this->app->share(function () {
            return new Controllers\MeetingsController(
                $this->app['meetings.service'],
                $this->app['version.service'],
                $this->app['auth.service'],
                array('path' => $this->app['gearmand.path'],'server' => $this->app['gearmand.server'])
            );
        });

        $this->app['photocall.controller'] = $this->app->share(function () {
            return new Controllers\PhotocallController(
                $this->app['photocall.service']
            );
        });

        $this->app['schedules.controller'] = $this->app->share(function () {
            return new Controllers\SchedulesController(
                $this->app['schedules.service'],
                $this->app['version.service'],
                $this->app['auth.service']
            );
        });

        $this->app['sponsors.controller'] = $this->app->share(function () {
            return new Controllers\SponsorsController(
                $this->app['sponsors.service'],
                $this->app['version.service'],
                $this->app['auth.service']
            );
        });

        $this->app['txokos.controller'] = $this->app->share(function () {
            return new Controllers\TxokosController(
                $this->app['txokos.service'],
                $this->app['version.service'],
                $this->app['auth.service']
            );
        });



    }

    public function bindRoutesToControllers()
    {
        $app = $this->app;
        $api = $this->app["controllers_factory"];

        $api->get('/assistants', "assistants.controller:getAll");
        $api->put('/assistants', "assistants.controller:update");

        $api->post('/auth/code', "auth.controller:authWithCode");
        $api->get('/auth/code/{code}', "auth.controller:authWithCodeFromGET");
        $api->get('/auth/hash', "auth.controller:authWithHash");
        $api->post('/auth/mail', "auth.controller:resendMail");

        $api->get('/expositors', "expositors.controller:getAll");

        $api->get('/link', "link.controller:getLink");

        $api->get('/meetings', "meetings.controller:getAll");
        $api->get('/meetings/{meetingId}', "meetings.controller:getOne");
        $api->post('/meetings', "meetings.controller:create");
        $api->put('/meetings', "meetings.controller:update");

        $api->get('/photocall', "photocall.controller:getAll");

        $api->get('/schedules', "schedules.controller:getAll");

        $api->get('/sponsors', "sponsors.controller:getAll");

        $api->get('/txokos', "txokos.controller:getAll");


        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);
    }
}
