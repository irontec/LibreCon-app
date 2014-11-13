<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ExpositorsController
{

    protected $expositorsService;
    protected $versionService;
    protected $authService;

    public function __construct($expositorsService, $versionService, $authService)
    {
        $this->expositorsService = $expositorsService;
        $this->versionService = $versionService;
        $this->authService = $authService;
    }
    
    public function getAll(Request $request)
    {
        $version = $request->query->get('v');

        $updated = $this->versionService->checkLastVersion('Expositor', $version);
        if($updated){

          return new JsonResponse(
              null,
              304
          );
        }

        return new JsonResponse(
            array(
                'status' => 'success',
                'data' => array(
                  'expositors' => $this->expositorsService->getAll(),
                  'version' => $this->versionService->getLastVersion('Expositor')
                )
            )
        );
    }
}
