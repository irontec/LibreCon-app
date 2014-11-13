<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhotocallController
{

  protected $authService;

  public function __construct($photocallService)
  {
    $this->photocallService = $photocallService;

  }

  public function getAll(Request $request)
  {

    return new JsonResponse(
      array(
        'status' => 'success',
        'data' => array(
          'photos' => $this->photocallService->getPhotos()
        )
      ),
      200
    );
  }

}
