<?php
    namespace App\Services;

    use App\Models\Order;

    class OrderService
    {
        public function get($id = null) 
        {
            if ($id) {
                return Order::select($id);
            } else {
                return Order::selectAll();
            }
        }

        public function post() 
        {
            $data = $_POST;

            return Order::insert($data);
        }
    }