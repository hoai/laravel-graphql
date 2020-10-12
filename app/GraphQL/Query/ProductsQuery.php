<?php

namespace App\GraphQL\Query;

use App\Product;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class ProductsQuery extends Query
{
    protected $attributes = [
        'name' => 'Products Query',
        'description' => 'A query of product'
    ];

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
        /*$where = function ($query) use ($args) {
            if (isset($args['id'])) {
                $query->where('id',$args['id']);
            }

            if (isset($args['title'])) {
                $query->where('title','like','%'.$args['title'].'%');
            }
        };*/

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
