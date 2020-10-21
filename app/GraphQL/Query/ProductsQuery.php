<?php

namespace App\GraphQL\Query;

use App\Product;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use App\Http\Services\ProductService;
use App\Http\Repositories\ProductRepository;

class ProductsQuery extends Query
{
    protected $attributes = [
        'name' => 'Products Query',
        'description' => 'A query of product'
    ];
    protected $productService;

    function __construct(ProductService $productService) {
        parent::__construct();
        $this->productService = $productService;

    }


    public function type()
    {
        return GraphQL::paginate('products');
    }

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::string()
            ],
            'title' => [
                'name' => 'title',
                'type' => Type::string()
            ],
            'limit' => [
                'name' => 'limit',
                'type' =>  Type::int()
            ],
            'page' => [
                'name' => 'page',
                'type' =>  Type::int()
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        return $this->productService->resolve($args, $fields);
    }
}
