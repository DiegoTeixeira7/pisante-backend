<?php
    namespace App\Models;

    class User
    {
        private static $table = 'users';

        public static function select(int $id) {    
            $connPdo = 'host='.DBHOST.' port='.DBPORT.' dbname='.DBNAME.' user='.DBUSER.' password='.DBPASS;
            $conexao = pg_connect($connPdo) or die('Erro de conexão: ' . pg_last_error());
            
            $query = 'SELECT * FROM address, users WHERE users.id_address = address.id AND users.id = '.$id;
            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;
            
            return pg_fetch_all($result);
        }

        public static function selectAll() {
            $connPdo = 'host='.DBHOST.' port='.DBPORT.' dbname='.DBNAME.' user='.DBUSER.' password='.DBPASS;
            $conexao = pg_connect($connPdo) or die('Erro de conexão: ' . pg_last_error());
            
            $query = 'SELECT * FROM address, users WHERE users.id_address = address.id';
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
            
            $values = $lastId.",'" . $data['first_name'] . "','" . $data['last_name'] . "','" . $data['phone'] . "','" . $data['cel'] . "','" . $data['email'] . "','" . $data['password'] . "','" . $data['gender'] . "','" . $data['cpf'] . "','" . 'authenticated' ."'";
            $query = 'INSERT INTO '.self::$table.' (id_address,first_name, last_name, phone, cel, email, password, gender, cpf, role) VALUES ('.$values.') RETURNING id';
           
            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;
            $lastId = -1;

            if ($result == false) {
              die( pg_last_error() );
            } else {
              $lastId = pg_fetch_array($result,0)[0];
            }

            return User::select($lastId);
        }

        public static function update($data)
        {
            $connPdo = 'host='.DBHOST.' port='.DBPORT.' dbname='.DBNAME.' user='.DBUSER.' password='.DBPASS;
            $conexao = pg_connect($connPdo) or die('Erro de conexão: ' . pg_last_error());
            
            $query = 'UPDATE '.self::$table.' SET first_name = '."'". $data['first_name'] ."'".', last_name = '."'".$data['last_name']."'".', phone = '."'".$data['phone']."'".', cel = '."'". $data['cel']. "'".', email = '."'". $data['email']. "'".', password = '."'". $data['password']."'".', gender = '."'". $data['gender']."'".', cpf = '. $data['cpf']."'"."'".', id_address = '. $data['id_address']. ' WHERE id = '.$data['id'];
            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;

            $query = 'UPDATE address SET street = '."'". $data['street'] ."'".', city = '."'".$data['city']."'".', state = '."'". $data['state'] ."'".', cep = '."'".$data['cep']."'".', country = '."'".$data['country']."'".', neighborhood = '."'".$data['neighborhood']."'".', number = '.$data['number']. ' WHERE id = '.$data['id_address'];
            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;

            return User::select($data['id']);
        }

        public static function login($data) {
            $connPdo = 'host='.DBHOST.' port='.DBPORT.' dbname='.DBNAME.' user='.DBUSER.' password='.DBPASS;
            $conexao = pg_connect($connPdo) or die('Erro de conexão: ' . pg_last_error());

            $query = 'SELECT * FROM '.self::$table.' WHERE email = '."'".$data['email']."'". ' AND password = '."'".$data['password']."'";
            
            $result = pg_query($conexao, $query) or die('Erro de operação: ' . pg_last_error());;
            $result = pg_fetch_all($result);
            
            return User::select($result[0]['id']);
        }
    }