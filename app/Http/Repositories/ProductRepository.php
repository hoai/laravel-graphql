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

        /*if (isset($args['id'])) {
            return Product::with(array_keys($fields->getRelations()))->where('id' , $args['id'])->select($fields->getSelect())->paginate();
        }

        if (isset($args['title'])) {
            return Product::with(array_keys($fields->getRelations()))->where('title', $args['title'])->select($fields->getSelect())->paginate();
        }

        $with = array_keys($fields->getRelations());
        return Product::with($with)->select($fields->getSelect())->paginate($args['limit'], ['*'], 'page', $args['page']);
        */
       $with = array_keys($fields->getRelations());
        //echo  json_encode($with); exit;
        $users = DB::table('products')
            ->join('product_images', 'products.id', '=', 'product_images.product_id')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->where('products.id' , $args['id'])->select($fields->getSelect())->paginate();
       echo  json_encode($users); exit;
        return $users;


    }
}
