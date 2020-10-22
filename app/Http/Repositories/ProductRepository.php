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

      /* if (isset($args['id'])) {
            return Product::with(array_keys($fields->getRelations()))->where('id' , $args['id'])->select($fields->getSelect())->paginate();
        }

        if (isset($args['title'])) {
            return Product::with(array_keys($fields->getRelations()))->where('title', $args['title'])->select($fields->getSelect())->paginate();
        }

        $with = array_keys($fields->getRelations());
        $users =  Product::with($with)->select($fields->getSelect())->paginate($args['limit'], ['*'], 'page', $args['page']);
        echo  json_encode($users); exit;*/

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
        $mm = $users->where('products.id' , $args['id'])->select(DB::raw( implode(",", $with_columns) ))->get()->toArray();//->paginate();
        //$mm = $mm3->toArray();
        //var_dump($mm3->toArray());exit;
        echo  json_encode($mm); exit;
        foreach($mm['data'] as $index => $row){
                foreach ($row as $key => $val) {
                    if (strpos($key, ".") !== FALSE) {
                       //echo $key;exit;
                    }
                }
        }
      //echo  json_encode($mm); exit;
        return $mm3;


    }
}
