<?php
  require_once __DIR__.'/../vendor/autoload.php';
  require_once __DIR__.'/../src/.php';

  use Symfony\Component\HttpFoundation\Request;
  Request::enableHttpMethodParameterOverride();
  use Symfony\Component\Debug\Debug;
  Debug::enable();

  $app = new Silex\Application();

  $server = 'mysql:host=localhost:8889;dbname=registrar';
  $username = 'root';
  $password = 'root';
  $DB = new PDO($server, $username, $password);

  $app['debug'] = true;

  $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'));

  $app->get("/", function() use($app){
      return $app['twig']->render('index.html.twig'));
  });

  return $app;
?>
