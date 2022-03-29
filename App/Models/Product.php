<?php
    namespace App\Models;

    class Product
    {
        private static $table = 'product';

        public static function select(int $id) {    
            $connPdo = 'host='.DBHOST.' port='.DBPORT.' dbname='.DBNAME.' user='.DBUSER.' password='.DBPASS;
            $conexao = pg_connect($connPdo) or die('Erro de conexão: ' . pg_last_error());
            
            $query = 'SELECT * FROM model, product WHERE product.id_model = model.id AND product.id = '.$id;
            
            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;
            return pg_fetch_all($result);
        }

        public static function selectAll() {
            $connPdo = 'host='.DBHOST.' port='.DBPORT.' dbname='.DBNAME.' user='.DBUSER.' password='.DBPASS;
            $conexao = pg_connect($connPdo) or die('Erro de conexão: ' . pg_last_error());
            
            $query = 'SELECT * FROM model, product WHERE product.id = model.id';
            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;
            
            return pg_fetch_all($result);
        }

        public static function insert($data)
        {
            $connPdo = 'host='.DBHOST.' port='.DBPORT.' dbname='.DBNAME.' user='.DBUSER.' password='.DBPASS;
            $conexao = pg_connect($connPdo) or die('Erro de conexão: ' . pg_last_error());
           
            $values = "'" . $data['brand'] . "','" . $data['material'] . "','" . $data['intended_audience'] . "','" . $data['closure'] . "','" . $data['is_shock_absorbers'] . "','" . $data['is_anti_odour_insole']."'";
            $query = 'INSERT INTO model (brand, material, intended_audience, closure, is_shock_absorbers, is_anti_odour_insole, inventory) VALUES'. '('.$values.',null) RETURNING id';

            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;
            $lastId = -1;

            if ($result == false) {    
              die( pg_last_error() );
            } else {
              $lastId = pg_fetch_array($result,0)[0];
            }

            $values = $lastId.",'". $data['title'] . "'," . $data['price'] . ",'" . $data['slug'] . "'," . $data['available_quantity'];
            $query = 'INSERT INTO '.self::$table.' (id_model, title, price, slug, available_quantity) VALUES ('.$values.') RETURNING id';
           
            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;
            
            $lastId = -1;

            if ($result == false) {    
              die( pg_last_error() );
            } else {
              $lastId = pg_fetch_array($result,0)[0];
            }

            return Product::select($lastId);
        }

        public static function update($data)
        {
            $connPdo = 'host='.DBHOST.' port='.DBPORT.' dbname='.DBNAME.' user='.DBUSER.' password='.DBPASS;
            $conexao = pg_connect($connPdo) or die('Erro de conexão: ' . pg_last_error());

            $query = 'UPDATE '.self::$table.' SET title = '."'". $data['title'] ."'".', price = '.$data['price'].', slug = '."'".$data['slug']."'".', available_quantity = '. $data['available_quantity']. ' WHERE id = '.$data['id'];
            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;
            
            $query = 'SELECT * FROM product WHERE id = '.$data['id'];
            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;
            
            $result = pg_fetch_all($result);

            $id_model = $result[0]['id_model'];

            $query = 'UPDATE model SET brand = '."'". $data['brand'] ."'".', material = '."'".$data['material']."'".', intended_audience = '."'". $data['intended_audience'] ."'".', closure = '."'".$data['closure'].', is_shock_absorbers = '.$data['is_shock_absorbers'].', is_anti_odour_insole = '.$data['is_anti_odour_insole']."'". ' WHERE id = '.$id_model;
            
            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;
            
            return Product::select($data['id']);
        }
    }