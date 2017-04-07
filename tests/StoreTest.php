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
class StoreTest extends PHPUnit_Framework_TestCase
{
  protected function tearDown()
  {
      Brand::deleteAll();
      Store::deleteAll();
  }

  function test_save()
  {
      $store = new Store("footlocker");
      $store->save();
      $result = Store::getAll();
      $this->assertEquals([$store], $result);
  }
  function test_deleteAll()
  {
      $store = new Store("footlocker");
      $store->save();
      $result = Store::deleteAll();
      $result = Store::getAll();
      $this->assertEquals([], $result);
  }
  function test_getAll()
  {
      $store = new Store("footlocker");
      $store->save();
      $store2 = new Store("ross");
      $store2->save();
      $result = Store::getAll();
      $this->assertEquals([$store, $store2], $result);
  }
  function test_update()
  {
      $store = new Store("footlocker");
      $store->save();
      $newname = "ross";
      $store->update($newname);
      $result = $store->getStoreName();
      $this->assertEquals($newname, $result);
  }
  function test_find()
  {
      $store = new Store("footlocker");
      $store->save();
      $store2 = new Store("ross");
      $store2->save();
      $result = Store::find($store2->getStoreId());
      $this->assertEquals($store2, $result);
  }
  function test_delete()
  {
      $store = new Store("footlocker");
      $store->save();
      $store2 = new Store("ross");
      $store2->save();
      $result = $store->delete();
      $result2 = Store::getAll();
      $this->assertEquals([$store2], $result2);
  }
}
?>
