<?php

use Silex\Application;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\ServicesLoader;
use App\RoutesLoader;
use Carbon\Carbon;

date_default_timezone_set('Europe/Madrid');


define("ROOT_PATH", __DIR__ . "/..");

//handling CORS preflight request
$app->before(function (Request $request) {
  //var_dump($response->headers);
   if ($request->getMethod() === "OPTIONS") {
       $response = new Response();
       $response->headers->set("Access-Control-Allow-Origin","*");
       $response->headers->set("Access-Control-Allow-Methods","GET,POST,PUT,DELETE,OPTIONS");
       $response->headers->set("Access-Control-Allow-Headers","Origin, X-Requested-With, Content-Type, Accept, Authorization");
       $response->setStatusCode(200);
       return $response->send();
   }
}, Application::EARLY_EVENT);

//handling CORS respons with right headers
$app->after(function (Request $request, Response $response) {

   $response->headers->set("Access-Control-Allow-Origin","*");
   $response->headers->set("Access-Control-Allow-Methods","GET,POST,PUT,DELETE,OPTIONS");
   $response->headers->set("Access-Control-Allow-Headers","Origin, X-Requested-With, Content-Type, Accept, Authorization");
   $response->headers->set("Access-Control-Allow-Credentials", "true");
});

//Handling multipart/form-data to put
$app->before(function (Request $request) {

  if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'multipart/form-data')
      && in_array(strtoupper($request->server->get('REQUEST_METHOD', 'GET')), array('PUT', 'DELETE', 'PATCH'))
  ) {
      $raw_data = file_get_contents('php://input');
      $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

      // Fetch each part
      $parts = array_slice(explode($boundary, $raw_data), 1);
      $data = array();

      foreach ($parts as $part) {
          // If this is the last part, break
          if ($part == "--\r\n") break;

          // Separate content from headers
          $part = ltrim($part, "\r\n");
          list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

          // Parse the headers list
          $raw_headers = explode("\r\n", $raw_headers);
          $headers = array();
          foreach ($raw_headers as $header) {
              list($name, $value) = explode(':', $header);
              $headers[strtolower($name)] = ltrim($value, ' ');
          }

          // Parse the Content-Disposition to get the field name, etc.
          if (isset($headers['content-disposition'])) {
              $filename = null;
              preg_match(
                  '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/',
                  $headers['content-disposition'],
                  $matches
              );
              list(, $type, $name) = $matches;
              isset($matches[4]) and $filename = $matches[4];

              // handle your fields here
              switch ($name) {
                  // this is a file upload

                  // default for all other files is to populate $data
                  default:
                       $data[$name] = substr($body, 0, strlen($body) - 2);

                       break;
              }
          }

      }
      $request->request = new ParameterBag($data);
  }
}, Application::EARLY_EVENT);

//accepting JSON
$app->before(function (Request $request) {

    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->register(new ServiceControllerServiceProvider());

if(getenv('APPLICATION_ENV') == 'development'){

$app->register(new DoctrineServiceProvider(), array(
	"db.options" => array(
        "driver" => "pdo_mysql",
        "dbname" => "dev-db-name",
        "user" => "dev-db-user",
        "password" => "dev-db-password",
        "path" => realpath(ROOT_PATH . "/app.db"),
    ),
));

}else{

$app->register(new DoctrineServiceProvider(), array(
  "db.options" => array(
        "driver" => "pdo_mysql",
        "dbname" => "prod-db-name",
        "user" => "prod-db-user",
        "password" => "prod-db-password",
        "path" => realpath(ROOT_PATH . "/app.db"),
    ),
));

}

$app->register(new HttpCacheServiceProvider(), array("http_cache.cache_dir" => ROOT_PATH . "/storage/cache",));

$app->register(new MonologServiceProvider(), array(
    "monolog.logfile" => ROOT_PATH . "/storage/logs/" . Carbon::now('Europe/London')->format("Y-m-d") . ".log",
    "monolog.level" => $app["log.level"],
    "monolog.name" => "application"
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/App/Views',
));

//load services
$servicesLoader = new App\ServicesLoader($app);
$servicesLoader->bindServicesIntoContainer();


//load routes
$routesLoader = new App\RoutesLoader($app);

$routesLoader->bindRoutesToControllers();

$app->error(function (\Exception $e, $code) use ($app) {
    $app['monolog']->addError($e->getMessage());
    $app['monolog']->addError($e->getTraceAsString());
    return new JsonResponse(array("statusCode" => $code, "message" => $e->getMessage(), "stacktrace" => $e->getTraceAsString()));
});





return $app;
