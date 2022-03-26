<?php
    namespace App\Services;

    use App\Models\Product;

    class ProductService
    {
        public function get($id = null) 
        {
            if ($id) {
                return Product::select($id);
            } else {
                return Product::selectAll();
            }
        }

        public function post() 
        {
            $data = $_POST;

            if($data['id']) {
                return Product::update($data);
            } else {
                return Product::insert($data);
            }
        }
    }