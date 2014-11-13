<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AssistantsController
{

    protected $assistantsService;
    protected $versionService;
    protected $authService;

    public function __construct($assistantsService, $versionService, $authService)
    {
        $this->assistantsService = $assistantsService;
        $this->versionService = $versionService;
        $this->authService = $authService;
    }

    public function getAll(Request $request)
    {
        $identity = $this->authService->getIdentity($request);

        if($identity['userId'] == null){
            return new JsonResponse(
                array(
                  'status' => 'fail',
                  'data' => array(
                    'message' => 'Unauthorized'
                  )
                ),
                401
            );
        }

        $version = $request->query->get('v');

        $updated = $this->versionService->checkLastVersion('Assistants', $version);
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
                  'assistants' => $this->assistantsService->getAll($identity['hash']),
                  'version' => $this->versionService->getLastVersion('Assistants')
                )
            )
        );
    }

    public function update(Request $request)
    {
      $identity = $this->authService->getIdentity($request);

      if($identity['userId'] == null){
          return new JsonResponse(
            array(
                'status' => 'fail',
                'data' => array(
                  'message' => 'Unauthorized'
                )
            ),
            401
          );
      }

      if(
        ($request->get('uuid') !== '' && !$request->get('uuid')) ||
        (!$request->get('device')) ||
        (!$request->get('lang'))
      ) {
        return new JsonResponse(
            array(
              'status' => 'fail',
              'data' => array(
                'message' => 'Bad Request'
              )
            ),
            400
        );
      } else {


        $uuid = $request->get('uuid');
        $device = $request->get('device');
        $lang = $request->get('lang');

        if (!in_array($lang, array("eu","es","en"))) {
            $lang = "es";
        }
      }

      $response = $this->assistantsService->update($identity['userId'], $uuid, $device, $lang);

      return new JsonResponse(
          array(
              'status' => 'success',
              'data' => array(
                'message' => $response['message']
              )
          ),
          $response['statusCode']
      );

    }

}
