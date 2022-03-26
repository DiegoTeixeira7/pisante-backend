<?php
    namespace App\Services;

    use App\Models\User;

    class UserService
    {
        public function get($id = null) 
        {
            if ($id) {
                return User::select($id);
            } else {
                return User::selectAll();
            }
        }

        public function post() 
        {
            $data = $_POST;

            if($data['id']) {
                return User::update($data);
            } else {
                if($data['cpf']) {
                    return User::insert($data);
                } else {
                    return User::login($data);
                }
            }
        }
    }