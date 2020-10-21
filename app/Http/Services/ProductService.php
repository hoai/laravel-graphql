<?php


namespace App\Http\Services;
use App\Http\Repositories\ProductRepository;
use App\Product;

class ProductService
{
    private $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function resolve( $args,  $fields)
    {
        if (isset($args['id'])) {
            return Product::with(array_keys($fields->getRelations()))->where('id' , $args['id'])->select($fields->getSelect())->paginate();
        }

        if (isset($args['title'])) {
            return Product::with(array_keys($fields->getRelations()))->where('title', $args['title'])->select($fields->getSelect())->paginate();
        }

        $with = array_keys($fields->getRelations());
        return Product::with($with)->select($fields->getSelect())->paginate($args['limit'], ['*'], 'page', $args['page']);

    }
}
