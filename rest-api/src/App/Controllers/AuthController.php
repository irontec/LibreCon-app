<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AuthController
{

    protected $authService;

    public function __construct($service, $gearmandOptions)
    {

        $this->_gearmandPath = $gearmandOptions['path'];
        $this->_gearmandServer = $gearmandOptions['server'];

        $this->authService = $service;
    }

    public function authWithCode(Request $request)
    {
      $code = $request->get('code');
      $code = strtoupper($code);
      return $this->authWithCodeFromGet($code);
    }

    public function authWithCodeFromGet($code)
    {

        $authenticatedUser = $this->authService->authenticate($code, "code");
        if($authenticatedUser){
            return new JsonResponse(
                    array(
                            'status' => 'success',
                            'data' => array(
                                    'assistant' => $authenticatedUser
                            )
                    )
            );
        }else{
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
    }
    
    public function authWithHash(Request $request)
    {
        $hash = $this->authService->check($request);

        if($hash == null){
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

        $authenticatedUser = $this->authService->authenticate($hash, "secretHash");

        if($authenticatedUser){
          return new JsonResponse(
              array(
                'status' => 'success',
                'data' => array(
                  'assistant' => $authenticatedUser
                )
              )
          );
        }else{
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
    }



    public function resendMail(Request $request)
    {

        $email = $request->get("email");
        $authenticatedUser = $this->authService->authenticate($email, "email");

        if (!$authenticatedUser) {

            return new JsonResponse(
                    array(
                            'status' => 'forbiden',
                            'data' => array(
                                    'message' => 'not found'
                            )
                    ),
                    401
            );

        }


        $this->_sendMail($authenticatedUser->id);


          return new JsonResponse(
              array(
                'status' => 'success',
                'data' => array(
                  'message' => 'Mensaje enviado satisfactoriamente'
                )
              ),
              200
          );
    }




    protected function _sendMail($userId)
    {

        require_once $this->_gearmandPath . '/Manager.php';
        require_once $this->_gearmandPath . '/Worker.php';

        \Iron_Gearman_Manager::setOptions(array(
                'servers' => array($this->_gearmandServer),
                'client' => array('timeout' => 2000),
        ));

        $job = array(
                'userId' => $userId
        );

        $gearmandClient = \Iron_Gearman_Manager::getClient();
        $r = $gearmandClient->doBackground("sendEmail", igbinary_serialize($job));

    }

}
