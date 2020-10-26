<?php


namespace App\Http\Repositories;
use App\Product;
use DB;

class ProductRepository
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    public function resolve($args,  $fields)
    {
        /*
      if (isset($args['id'])) {
            $users =  Product::with(array_keys($fields->getRelations()))->where('id' , $args['id'])->select($fields->getSelect())->paginate();

            echo  json_encode($users->toArray());exit;

            return $users;
        }

        if (isset($args['title'])) {
            return Product::with(array_keys($fields->getRelations()))->where('title', $args['title'])->select($fields->getSelect())->paginate();
        }

        $with = array_keys($fields->getRelations());
        $users =  Product::with($with)->select($fields->getSelect())->paginate($args['limit'], ['*'], 'page', $args['page']);
        echo  json_encode($users->toArray()); exit; */

        //echo  json_encode($fields->getSelect()); exit;

        $with = array_keys($fields->getRelations());
        $with_columns = [];
        /*$products_columns  = DB::select( DB::raw("SELECT GROUP_CONCAT(CONCAT(' ', table_name,'.', column_name, ' as `', table_name, '.', column_name, '`')) as name FROM information_schema.columns WHERE table_name = :table_variable"), array(
            'table_variable' => 'products'
        ));*/
        $with_columns['products'] = implode(",", $fields->getSelect());//$products_columns[0]->name; //
        foreach($with as $key => $val){

            $rr  = DB::select( DB::raw("SELECT GROUP_CONCAT(CONCAT(' ', table_name,'.', column_name, ' as `', table_name, '.', column_name, '`')) as name FROM information_schema.columns WHERE table_name = :table_variable"), array(
                'table_variable' => $val
            ));
            $with_columns[$val] = $rr[0]->name;
        }

        //echo  json_encode($with); exit;
        $users = DB::table('products');
        if(in_array('product_images', $with)){
            $users->join('product_images', 'products.id', '=', 'product_images.product_id');
        }
        if(in_array('users', $with)){
            $users->join('users', 'products.user_id', '=', 'users.id');
        }
        $result = [];
        if (isset($args['id'])) {
            $mm = $users->where('products.id' , $args['id'])->select(DB::raw( implode(",", $with_columns) ))->get()->toArray();//->paginate();

        }
        else if (isset($args['title'])) {
            $mm = $users->where('products.title' , $args['title'])->select(DB::raw( implode(",", $with_columns) ))->get()->toArray();//->paginate();

        }
        else {
             $mm = $users->select(DB::raw( implode(",", $with_columns) ))->get()->toArray();//->paginate();
        }
        //return array_pop($mm);
        //$mm = $mm3->toArray();
        //var_dump($mm);exit;
        //echo  json_encode($mm); exit;
        foreach($mm as $index => $row){
                $row  = (array) $row;
                foreach ($row as $key => $val) {
                    if (strpos($key, ".") !== FALSE) {
                       //echo $key;exit;
                        $child_group = explode(".", $key);

                        if($child_group[1] == 'id'){
                            $child_key_id = $val;
                        }

                        $result[$row['id']][$child_group[0]][$child_key_id][$child_group[1]] = $val;
                    }
                    else {
                        $result[$row['id']][$key] = $val;
                    }
                }
        }
      //echo  json_encode(array_values($result)); exit;
     // echo  json_encode(array_pop($result)); exit;
        //return array_pop($result);
        return array_values($result);


    }
}
