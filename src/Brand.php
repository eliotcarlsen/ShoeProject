<?php
    class Brand
    {
        private $brand_name;
        private $id;

        function __construct($brand_name, $id = null)
        {
            $this->brand_name = $brand_name;
            $this->id = $id;
        }
        function setBrandName($new_brand_name)
        {
            $this->brand_name = $new_brand_name;
        }
        function getBrandName()
        {
            return $this->brand_name;
        }
        function getBrandId()
        {
          return $this->id;
        }
        function save()
        {
            $executed = $GLOBALS['DB']->exec("INSERT INTO brands (brand_name) VALUES ('{$this->getBrandName()}')");
            if($executed){
              $this->id = $GLOBALS['DB']->lastInsertId();
              return true;
            }else{
              return false;
            }
        }
        static function getAll()
        {
          $returned_brands = $GLOBALS['DB']->query("SELECT * FROM brands;");
          $brands = array();
          foreach($returned_brands as $brand)
          {
              $name = $brand['brand_name'];
              $id = $brand['id'];
              $new_brand_name = new Brand($name, $id);
              array_push($brands, $new_brand_name);
          }
          return $brands;
        }
        static function deleteAll()
        {
            $executed = $GLOBALS['DB']->exec("DELETE FROM brands;");
            if ($executed)
            {
              return true;
            } else {
              return false;
            }
        }
        function update($new_brand_name)
        {
            $executed = $GLOBALS['DB']->exec("UPDATE brands SET brand_name = '{$new_brand_name}' WHERE id = {$this->getBrandId()};");
            if ($executed){
              $this->setBrandName($new_brand_name);
              return true;
            } else {
              return false;
            }
        }
        static function find($id)
        {
            $returned_brands = $GLOBALS['DB']->prepare("SELECT * FROM brands WHERE id = :id");
            $returned_brands->bindParam(':id', $id, PDO::PARAM_STR);
            $returned_brands->execute();
            foreach($returned_brands as $brand){
              $name = $brand['brand_name'];
              $brand_id = $brand['id'];
              if($brand_id == $id){
                $found_brand = new Brand($name, $id);
                return $found_brand;
              }
            }
        }
        static function findBrandName($brand_name)
        {
            $returned_brands = $GLOBALS['DB']->prepare("SELECT * FROM brands WHERE brand_name = :name;");
            $returned_brands->bindParam(':name', $brand_name, PDO::PARAM_STR);
            $returned_brands->execute();
            foreach($returned_brands as $brand){
              $name = $brand['brand_name'];
              $brand_id = $brand['id'];
              if($name == $brand_name){
                $found_brand = new Brand($name, $brand_id);
                return $found_brand;
              }
            }
        }
        function findStores()
        {
            $returned_stores = $GLOBALS['DB']->query("SELECT stores.* FROM stores JOIN brands_stores ON (brands_stores.store_id = stores.id) JOIN brands ON (brands_stores.brand_id = brands.id) WHERE brands.id = {$this->getBrandId()};");
            $stores = array();
            foreach($returned_stores as $store) {
              $name = $store['store_name'];
              $id = $store['id'];
              $new_store = new Store($name, $id);
              array_push($stores, $new_store);
            }
            return $stores;
        }
        function addStore($store_id)
        {
            $executed = $GLOBALS['DB']->exec("INSERT INTO brands_stores (brand_id, store_id) VALUES ({$this->getBrandId()}, {$store_id});");
            if($executed){
              return true;
            } else {
              return false;
            }
        }
        static function checkBrands() {
            $brands = array();
            $executed = $GLOBALS['DB']->query("SELECT * FROM brands;");
            $results = $executed->fetchAll(PDO::FETCH_ASSOC);
            foreach($results as $result){
             array_push($brands, $result['brand_name']);
            }
            return $brands;
        }
        static function checkStoresThatCarryBrand($brand_id) {
            $stores = array();
            $executed = $GLOBALS['DB']->query("SELECT stores.* FROM stores JOIN brands_stores ON (brands_stores.store_id = stores.id) JOIN brands ON (brands_stores.brand_id = brands.id) WHERE brands.id = {$brand_id};");
            $results = $executed->fetchAll(PDO::FETCH_ASSOC);
            foreach($results as $result){
             array_push($stores, $result['store_name']);
            }
            return $stores;
        }



      }
?>
