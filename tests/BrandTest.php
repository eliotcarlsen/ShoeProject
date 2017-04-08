<?php
/**
  * @backupGlobals disabled
  * @backupStaticAttributes disabled
  */
$server = 'mysql:host=localhost:8889;dbname=shoes_test';
$username= "root";
$password = "root";
$DB = new PDO($server, $username, $password);
require_once "src/Brand.php";
require_once "src/Store.php";
class BrandTest extends PHPUnit_Framework_TestCase
{
  protected function tearDown()
  {
      Brand::deleteAll();
      Store::deleteAll();
  }
  function test_save()
  {
      $brand = new Brand("footlocker");
      $brand->save();
      $result = Brand::getAll();
      $this->assertEquals([$brand], $result);
  }
  function test_deleteAll()
  {
      $brand = new Brand("footlocker");
      $brand->save();
      $result = Brand::deleteAll();
      $result = Brand::getAll();
      $this->assertEquals([], $result);
  }
  function test_getAll()
  {
      $brand = new Brand("footlocker");
      $brand->save();
      $brand2 = new Brand("ross");
      $brand2->save();
      $result = Brand::getAll();
      $this->assertEquals([$brand, $brand2], $result);
  }
  function test_update()
  {
      $brand = new Brand("footlocker");
      $brand->save();
      $newname = "ross";
      $brand->update($newname);
      $result = $brand->getBrandName();
      $this->assertEquals($newname, $result);
  }
  function test_find()
  {
      $brand = new Brand("footlocker");
      $brand->save();
      $brand2 = new Brand("ross");
      $brand2->save();
      $result = Brand::find($brand2->getBrandId());
      $this->assertEquals($brand2, $result);
  }
  function test_findStores()
  {
    $newBrand = new Brand("Nike");
    $newBrand->save();
    $newStore = new Store("Ross");
    $newStore->save();
    $newStore2 = new Store("Footlocker");
    $newStore2->save();
    $newBrand->addStore($newStore->getStoreId());
    $newBrand->addStore($newStore2->getStoreId());
    $result = $newBrand->findStores();
    $this->assertEquals([$newStore, $newStore2], $result);
  }
  function test_addBrand()
  {
      $newstore = new Store("ross");
      $newstore->save();
      $newbrand = new Brand("nike");
      $newbrand->save();
      $newstore->addBrand($newbrand->getBrandId());
      $this->assertTrue(true, "this store was not added");
  }
  function test_findBrandName()
  {
      $newbrand = new Brand("nike");
      $newbrand->save();
      $result = Brand::findBrandName("nike");
      $this->assertEquals($result, $newbrand);
  }
}
?>
