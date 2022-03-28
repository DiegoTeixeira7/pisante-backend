<?php
    namespace App\Models;

    class Order
    {
        private static $table = 'orders';

        public static function select(int $id) {    
            $connPdo = 'host='.DBHOST.' port='.DBPORT.' dbname='.DBNAME.' user='.DBUSER.' password='.DBPASS;
            $conexao = pg_connect($connPdo) or die('Erro de conexão: ' . pg_last_error());
            
            $query = 'SELECT * FROM users, orders, address WHERE orders.id_user = users.id AND orders.id_address = address.id AND orders.id = '.$id;

            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;
            return pg_fetch_all($result);
        }

        public static function selectAll() {
            $connPdo = 'host='.DBHOST.' port='.DBPORT.' dbname='.DBNAME.' user='.DBUSER.' password='.DBPASS;
            $conexao = pg_connect($connPdo) or die('Erro de conexão: ' . pg_last_error());
            
            $query = 'SELECT * FROM users, orders, address WHERE orders.id_user = users.id AND orders.id_address = address.id';
            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;
            
            return pg_fetch_all($result);
        }

        public static function insert($data)
        {
            $connPdo = 'host='.DBHOST.' port='.DBPORT.' dbname='.DBNAME.' user='.DBUSER.' password='.DBPASS;
            $conexao = pg_connect($connPdo) or die('Erro de conexão: ' . pg_last_error());
           
            $values = "'" . $data['street'] . "','" . $data['city'] . "','" . $data['state'] . "','" . $data['cep'] . "','" . $data['country'] . "','" . $data['neighborhood']."',". $data['number'];
            $query = 'INSERT INTO address (street, city, state, cep, country, neighborhood, number) VALUES'. '('.$values.') RETURNING id';

            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;
            $lastId = -1;

            if ($result == false) {    
              die( pg_last_error() );
            } else {
              $lastId = pg_fetch_array($result,0)[0];
            }

            $values = $lastId.",'". $data['date'] . "'," . $data['total'] . ",'" . $data['hour'] ."','{" . $data['products'] . "}'," . $data['id_user'];
            $query = 'INSERT INTO '.self::$table.' (id_address, date, total, hour, products, id_user) VALUES ('.$values.') RETURNING id';
            
            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;
            $lastId = -1;

            if ($result == false) {    
              die( pg_last_error() );
            } else {
              $lastId = pg_fetch_array($result,0)[0];
            }

            return Order::select($lastId);
        }
    }