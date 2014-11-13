<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class MeetingsController
{
    protected $meetingsService;
    protected $authService;
    protected $versionService;

    protected $_gearmandPath;
    protected $_gearmandServer;


    public function __construct($meetingsService, $versionService, $authService, $gearmandOptions)
    {
        $this->meetingsService = $meetingsService;
        $this->authService = $authService;
        $this->versionService = $versionService;

        $this->_gearmandPath = $gearmandOptions['path'];
        $this->_gearmandServer = $gearmandOptions['server'];
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

        $updated = false;
        $updated = $this->versionService->checkLastVersion('Meeting', $version);
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
                  'meetings' => $this->meetingsService->getAll($identity['userId']),
                  'version' => $this->versionService->getLastVersion('Meeting')
                )
            )
        );
    }

    public function getOne($meetingId, Request $request)
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

      $response = $this->meetingsService->getOne($identity['userId'], $meetingId);

      return new JsonResponse(
          array(
              'status' => $response['status'],
              'data' => $response['data']
          ),
          $response['statusCode']
      );
    }

    public function create(Request $request)
    {
        $identity = $this->authService->getIdentity($request);

        if($identity['userId'] == null || $identity['userId'] == $request->get('receiver')){
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

        if(!$request->get('receiver')){
          return new JsonResponse(
              array(
                'status' => 'fail',
                'data' => array(
                  'message' => 'Bad Request'
                )
              ),
              400
          );
        }else{
          $receiver = $request->get('receiver');
        }

        $response = $this->meetingsService->create($identity['userId'], $receiver);




        if($response['status'] == 'success'){

          $this->versionService->update('Meeting');
          $this->_sendPush('question', $response['id'], $identity['userId'], $receiver);

        }

        return new JsonResponse(
          array(
            'status' => $response['status'],
            'data' => array(
              'message' => $response['message']
            )
          ),
          $response['statusCode']
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

        if(!$request->get('meetingId')){
          return new JsonResponse(
              array(
                'status' => 'fail',
                'data' => array(
                  'message' => 'Bad Request'
                )
              ),
              400
          );
        }else{
          $meetingId = $request->get('meetingId');
        }

        $options = [];

        $options['cellphoneShare'] = false;
        if($request->get('cellphoneShare') != null){
            $options['cellphoneShare'] = $request->get('cellphoneShare');
        }

        $options['emailShare'] = false;
        if($request->get('emailShare') != null){
            $options['emailShare'] = $request->get('emailShare');
        }

        $options['moment'] = ''; //now, half, hour, never
        if($request->get('moment') != null){
            $options['moment'] = $request->get('moment');
        }

        $response = $this->meetingsService->update($identity['userId'], $meetingId, $options);

        if($response['status'] == 'success'){
          $this->versionService->update('Meeting');
        }


        if (!empty($options['moment']) && $response['status'] == 'success') {
            $this->_sendPush('answer', $meetingId, $identity['userId'], $response['sender']);
        }

        return new JsonResponse(
            array(
              'status' => $response['status'],
              'data' => array(
                'message' => $response['message']
              )
            ),
            $response['statusCode']
        );


    }

    protected function _sendPush($type, $meetingId, $sourceId, $targetId)
    {

        require_once $this->_gearmandPath . '/Manager.php';
        require_once $this->_gearmandPath . '/Worker.php';

        \Iron_Gearman_Manager::setOptions(array(
                'servers' => array($this->_gearmandServer),
                'client' => array('timeout' => 2000),
        ));

        $job = array(
                'userSourceId' => $sourceId,
                'userDestinationId' =>$targetId,
                'meetingId' => $meetingId,
                'msgType' => $type
        );

        $gearmandClient = \Iron_Gearman_Manager::getClient();
        $r = $gearmandClient->doBackground("sendPush", igbinary_serialize($job));

    }


}
