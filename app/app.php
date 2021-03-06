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
  $app->post("/add_brand", function() use($app){
      $brands_array = Brand::checkBrands();
      $stores_array = Store::checkStores();
      if (in_array($_POST['brand_name'], $brands_array) && (in_array($_POST['store_name'], $stores_array)))
      {
          return $app['twig']->render('all_brands.html.twig', array('brands'=>Brand::getAll()));
      } elseif (in_array($_POST['brand_name'], $brands_array) && (in_array($_POST['store_name'], $stores_array) == 0))
      {
          $brand_name = Brand::findBrandName($_POST['brand_name']);
          $brand_id = $brand_name->getBrandId();
          $store = new Store($_POST['store_name']);
          $store->save();
          $store->addBrand($brand_id);
          return $app['twig']->render('all_brands.html.twig', array('brands'=>Brand::getAll()));
      } elseif (in_array($_POST['store_name'], $stores_array) && (in_array($_POST['brand_name'], $brands_array) == 0))
      {
          $store_name = Store::findStoreName($_POST['store_name']);
          $brand = new Brand($_POST['brand_name']);
          $brand->save();
          $brand->addStore($store_name->getStoreId());
          return $app['twig']->render('all_brands.html.twig', array('brands'=>Brand::getAll()));
      }
  });
  $app->get("/single_brand/{id}", function($id) use($app){
      $brand = Brand::find($id);
      $stores = $brand->findStores();
      return $app['twig']->render('single_brand.html.twig', array('brand'=>$brand, 'stores'=>$stores));
  });
  $app->post("/add_store/{id}", function($id) use($app){
      $brand = Brand::find($id);
      $stores_array = $brand->checkStoresThatCarryBrand($id);
      if (in_array($_POST['store_name'], $stores_array))
      {
          $stores = $brand->findStores();
          return $app['twig']->render('single_brand.html.twig', array('brand'=>$brand, 'stores'=>$stores));
      } else {
          $store = new Store($_POST['store_name']);
          $store->save();
          $brand->addStore($store->getStoreId());
          $stores = $brand->findStores();
          return $app['twig']->render('single_brand.html.twig', array('brand'=>$brand, 'stores'=>$stores));
      }
  });
  $app->get("/all_stores", function() use($app){
      return $app['twig']->render('all_stores.html.twig', array('stores'=>Store::getAll()));
  });
  $app->post("/add_store", function() use($app){
      $stores = Store::checkStores();
      if (in_array($_POST['store_name'], $stores))
      {
          return $app['twig']->render('all_stores.html.twig', array('stores'=>Store::getAll()));
      } else {
          $store = new Store($_POST['store_name']);
          $store->save();
          return $app['twig']->render('all_stores.html.twig', array('stores'=>Store::getAll()));
    }
  });
  $app->post("/add_brand/{id}", function($id) use($app){
      $store = Store::find($id);
      $stores = $store->checkBrandsInStore($store->getStoreId());
      if (in_array($_POST['brand_name'], $stores))
      {
        $brands = $store->findBrands();
        return $app['twig']->render('single_store.html.twig', array('store'=>$store, 'brands'=>$brands));
      } else {
          $brand = new Brand($_POST['brand_name']);
          $brand->save();
          $store->addBrand($brand->getBrandId());
          $brands = $store->findBrands();
          return $app['twig']->render('single_store.html.twig', array('store'=>$store, 'brands'=>$brands));
      }
  });
  $app->get("/single_store/{id}", function($id) use($app){
      $store = Store::find($id);
      $brands = $store->findBrands();
      return $app['twig']->render('single_store.html.twig', array('store'=>$store, 'brands'=>$brands));
  });
  $app->patch("/edit_store/{id}", function($id) use($app){
      $name = $_POST['store_name'];
      $store = Store::find($id);
      $store->update($name);
      $brands = $store->findBrands();
      return $app['twig']->render('single_store.html.twig', array('brands'=>$brands, 'store'=>$store));
  });
  $app->delete("/delete_store/{id}", function($id) use($app){
      $store = Store::find($id);
      $store->delete();
      $brands = $store->findBrands();
      return $app['twig']->render('delete_success.html.twig', array('store'=>$store, 'brands'=>$brands));
  });
  $app->post("/delete_all_stores", function() use($app){
      Store::deleteAll();
      return $app['twig']->render('all_stores.html.twig', array('stores'=>Store::getAll()));
  });



  return $app;
?>
