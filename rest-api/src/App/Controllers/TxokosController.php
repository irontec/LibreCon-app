<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TxokosController
{

    protected $txokosService;
    protected $versionService;
    protected $authService;

    public function __construct($txokosService, $versionService, $authService)
    {
        $this->txokosService = $txokosService;
        $this->versionService = $versionService;
        $this->authService = $authService;
    }

    public function getAll(Request $request)
    {
        $version = $request->query->get('v');

        $updated = $this->versionService->checkLastVersion('Txoko', $version);
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
                "txokos" => $this->txokosService->getAll(),
                "version" => $this->versionService->getLastVersion("Txoko")
              )

            )
        );
    }
}
