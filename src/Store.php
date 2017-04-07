<?php
    class Store
    {
        private $store_name;
        private $id;

        function __construct($store_name, $id = null)
        {
            $this->store_name = $store_name;
            $this->id = $id;
        }
        function setStoreName($new_store_name)
        {
            $this->store_name = $new_store_name;
        }
        function getStoreName()
        {
            return $this->store_name;
        }
        function getStoreId()
        {
          return $this->id;
        }
        function save()
        {
            $executed = $GLOBALS['DB']->exec("INSERT INTO stores (store_name) VALUES ('{$this->getStoreName()}')");
            if($executed){
              $this->id = $GLOBALS['DB']->lastInsertId();
              return true;
            }else{
              return false;
            }
        }
        static function getAll()
        {
            $returned_stores = $GLOBALS['DB']->query("SELECT * FROM stores;");
            $stores = array();
            foreach($returned_stores as $store)
            {
              $name = $store['store_name'];
              $id = $store['id'];
              $new_store_name = new Store($name, $id);
              array_push($stores, $new_store_name);
            }
            return $stores;
        }
        static function deleteAll()
        {
            $executed = $GLOBALS['DB']->exec("DELETE FROM stores;");
            if (!$executed) {
              return false;
            }
            $executed = $GLOBALS['DB']->exec("DELETE FROM brands_stores;");
            if (!$executed){
              return false;
            } else {
              return true;
            }
        }
        function update($new_store_name)
        {
            $executed = $GLOBALS['DB']->exec("UPDATE stores SET store_name = '{$new_store_name}' WHERE id = {$this->getStoreId()};");
            if ($executed){
              $this->setStoreName($new_store_name);
              return true;
            } else {
              return false;
            }
        }
        static function find($id)
        {
            $returned_stores = $GLOBALS['DB']->prepare("SELECT * FROM stores WHERE id = :id");
            $returned_stores->bindParam(':id', $id, PDO::PARAM_STR);
            $returned_stores->execute();
            foreach($returned_stores as $store){
              $name = $store['store_name'];
              $store_id = $store['id'];
              if($store_id == $id){
                $found_store = new Store($name, $store_id);
                return $found_store;
              }
            }
        }
        function delete()
        {
            $executed = $GLOBALS['DB']->exec("DELETE FROM stores WHERE id = {$this->getStoreId()};");
            if ($executed) {
              return true;
            } else {
              return false;
            }
        }













      }
?>
