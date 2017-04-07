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
}
?>
