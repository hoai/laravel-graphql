<?php


namespace App\Http\Repositories;
use App\Product;

class ProductRepository
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    public function create($attributes)
    {
        return $this->product->create($attributes);
    }
}
