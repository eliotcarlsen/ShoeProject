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
            $executed = $GLOBALS['DB']->exec("INSERT INTO stores (store_name, id) VALUES ('{$this->getName()}')");
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
              array_push($store, $new_store_name);
            }
            return $stores;
        }
        static function deleteAll()
        {
            $executed = $GLOBALS['DB']->exec("DELETE * FROM stores;");
            if ($executed) {
              return true;
            } else {
              return false;
            }
        }
        function update($new_store_name)
        {
            $executed = $GLOBALS['DB']->exec("UPDATE stores SET store_name = '{$new_store_name}' WHERE id = {$this->getStoreId()};");
            if ($executed){
              return true;
            } else {
              return false;
            }
        }
        function findStore($id)
        {

        }











      }
?>
