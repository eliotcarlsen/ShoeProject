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











      }
?>
