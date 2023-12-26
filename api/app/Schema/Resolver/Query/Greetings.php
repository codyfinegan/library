<?php

namespace Library\Schema\Resolver\Query;

use Library\Schema\Resolver\Input\HelloInput;
use Library\Schema\Resolver\QueryResolver;

class Greetings implements QueryResolver
{
    /**
     * @param array $args
     * @param $context
     * @return string
     */
    public function resolve(array $args, $context): string
    {
        /** @var HelloInput $input */
        $input = $args['input'] ?? null;
        if (empty($input)) {
            return 'Hey there anonymous';
        }

        return "Hey there {$input->firstName} {$input->lastName}!";
    }

}