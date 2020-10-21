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
        return $this->productRepository->resolve( $args,  $fields);
    }
}
