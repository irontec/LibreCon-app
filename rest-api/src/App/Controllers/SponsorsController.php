<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SponsorsController
{

    protected $sponsorsService;
    protected $versionService;
    protected $authService;

    public function __construct($sponsorsService, $versionService, $authService)
    {
        $this->sponsorsService = $sponsorsService;
        $this->versionService = $versionService;
        $this->authService = $authService;
    }
    
    public function getAll(Request $request)
    {
        $version = $request->query->get('v');

        $updated = $this->versionService->checkLastVersion('Sponsors', $version);
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
                "sponsors" => $this->sponsorsService->getAll(),
                "version" => $this->versionService->getLastVersion("Sponsors")
              )

            )
        );
    }
}
