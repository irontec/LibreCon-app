<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SchedulesController
{

    protected $schedulesService;
    protected $versionService;
    protected $authService;

    public function __construct($schedulesService, $versionService, $authService)
    {
        $this->schedulesService = $schedulesService;
        $this->versionService = $versionService;
        $this->authService = $authService;
    }

    public function getAll(Request $request)
    {
        $version = $request->query->get("v");
        $day = $request->query->get("day");

        $updated = $this->versionService->checkLastVersion('Schedule', $version);
        if($updated){

          return new JsonResponse(
              null,
              304
          );
        }

        return new JsonResponse(
            array(
              "status" => "success",
              "data" => array(
                "schedules" => $this->schedulesService->getAll($day),
                "version" => $this->versionService->getLastVersion("Schedule")
              )

            )
        );
    }
}
