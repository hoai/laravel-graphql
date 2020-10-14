<?php

namespace App\GraphQL\Query;

use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'Users Query',
        'description' => 'A query of users'
    ];

    public function type(): Type
    {
        return GraphQL::paginate('users');
        //return Type::listOf(GraphQL::type('users'));
    }

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int()
            ],
            'email' => [
                'name' => 'email',
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

            if (isset($args['email'])) {
                $query->where('email',$args['email']);
            }
        };*/
        //print_r(array_keys($fields->getRelations()));exit;

        if (isset($args['id'])) {
            $mm =  User::with(array_keys($fields->getRelations()))->where('id' , $args['id'])->select($fields->getSelect())->paginate();
            //var_export($mm);exit;
            return $mm;
        }

        if (isset($args['email'])) {
            return User::with(array_keys($fields->getRelations()))->where('email', $args['email'])->select($fields->getSelect())->paginate();
        }


        return User::with(array_keys($fields->getRelations()))->select($fields->getSelect())->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
