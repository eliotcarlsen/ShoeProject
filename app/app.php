<?php
  require_once __DIR__.'/../vendor/autoload.php';
  require_once __DIR__.'/../src/Store.php';
  require_once __DIR__.'/../src/Brand.php';

  use Symfony\Component\HttpFoundation\Request;
  Request::enableHttpMethodParameterOverride();
  use Symfony\Component\Debug\Debug;
  Debug::enable();

  $app = new Silex\Application();

  $server = 'mysql:host=localhost:8889;dbname=shoes';
  $username = 'root';
  $password = 'root';
  $DB = new PDO($server, $username, $password);

  $app['debug'] = true;

  $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'));

  $app->get("/", function() use($app){
      return $app['twig']->render('index.html.twig');
  });
  $app->get("/all_brands", function() use($app){
      return $app['twig']->render('all_brands.html.twig', array('brands'=>Brand::getAll()));
  });
  $app->get("/all_stores", function() use($app){
      return $app['twig']->render('all_stores.html.twig', array('stores'=>Store::getAll()));
  });
  $app->post("/add_store", function() use($app){
      $store = new Store($_POST['store_name']);
      $store->save();
      return $app['twig']->render('all_stores.html.twig', array('stores'=>Store::getAll()));
  });
  $app->post("/add_brand", function() use($app){
      $brand = new Brand($_POST['brand_name']);
      $brand->save();
      $store = new Store($_POST['store_name']);
      $store->save();
      $store->addBrand($brand->getBrandId());
      return $app['twig']->render('all_brands.html.twig', array('brands'=>Brand::getAll()));
  });
  $app->get("/single_store/{id}", function($id) use($app){
      $store = Store::find($id);
      $brands = $store->findBrands();
      return $app['twig']->render('single_store.html.twig', array('store'=>$store, 'brands'=>$brands));
  });
  $app->get("/edit_store/{id}", function($id) use($app){
      return $app['twig']->render('single_store.html.twig');
  });
  $app->get("/delete_store/{id}", function($id) use($app){
      return $app['twig']->render('single_store.html.twig');
  });



  return $app;
?>
