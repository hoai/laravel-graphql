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

        $with = array_keys($fields->getRelations());

        $products_columns  = DB::select( DB::raw("SELECT GROUP_CONCAT(CONCAT(' ', table_name,'.', column_name, ' as `', table_name, '.', column_name, '`')) as name FROM information_schema.columns WHERE table_name = :table_variable"), array(
            'table_variable' => 'products'
        ));
        $products_images_columns  = DB::select( DB::raw("SELECT GROUP_CONCAT(CONCAT(' ', table_name,'.', column_name, ' as `', table_name, '.', column_name, '`')) as name FROM information_schema.columns WHERE table_name = :table_variable"), array(
            'table_variable' => 'product_images'
        ));
        $users_columns  = DB::select( DB::raw("SELECT GROUP_CONCAT(CONCAT(' ', table_name,'.', column_name, ' as `', table_name, '.', column_name, '`')) as name FROM information_schema.columns WHERE table_name = :table_variable"), array(
            'table_variable' => 'users'
        ));
       //print_r($products_columns);exit;

        echo  json_encode($products_columns[0]->name); exit;
        $users = DB::table('products')
            ->join('product_images', 'products.id', '=', 'product_images.product_id')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->where('products.id' , $args['id'])->select(DB::raw( $products_columns[0]->name ))->paginate();

       echo  json_encode($users); exit;
        return $users;


    }
}
