<?php

namespace Library\Schema\Resolver\Mutation;

use Library\Schema\Resolver\MutationResolver;

class Login implements MutationResolver
{
    public function resolve(array $args, $context): string
    {
        $input = $args['input'] ?? null;
        if (empty($input)) {
            return 'Hey there anonymous';
        }

        return "Hey there {$input['firstName']} {$input['lastName']}!";
    }

}