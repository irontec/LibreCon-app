<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class LinkController
{
    protected $twig;
    protected $authService;

    public function __construct($twig, $authService)
    {
        $this->twig = $twig;
        $this->authService = $authService;
    }

    public function getLink(Request $request)
    {
      $hash = $request->query->get("hash");

      $isValid = $this->authService->checkFromValue($hash);

      if($isValid){
        return $this->twig->render('link.html.twig', array(
          "hash" => $hash
        ));
      }else{
        return $this->twig->render('error.html.twig');
      }

    }
}
