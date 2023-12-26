<?php

namespace Library\Schema;

use DI\Container;
use Exception;
use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\AST\TypeDefinitionNode;
use GraphQL\Language\Parser;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Schema;
use GraphQL\Utils\BuildSchema;
use Library\Schema\Resolver\MutationResolver;
use Library\Schema\Resolver\QueryResolver;

class Resolver
{
    protected static Container $container;

    public static function load(Container $container): Schema
    {
        self::$container = $container;

        // @TODO - $contents should be cached
        $contents = Parser::parse(file_get_contents(__DIR__ . '/schema.graphql'));
        $typeConfigDecorator = [static::class, 'typeConfigDecorator'](...);
        $schema = BuildSchema::build($contents, $typeConfigDecorator);

        // @TODO - only do during dev
        $schema->assertValid();

        return $schema;
    }

    protected static function typeConfigDecorator(array $typeConfig, TypeDefinitionNode $typeDefinitionNode): array {
        if ($typeDefinitionNode instanceof ObjectTypeDefinitionNode) {
            $typeConfig['resolveField'] = [static::class, 'resolveField'](...);
        }

        // @TODO type resolution
        if ($typeDefinitionNode instanceof InterfaceTypeDefinitionNode) {
            $typeConfig['resolveType'] = function ($value, $context, ResolveInfo $info) {
                var_dump($value, $context, $info->fieldName);
                exit;
            };
        }

        // Inputs
        if ($typeDefinitionNode instanceof InputObjectTypeDefinitionNode) {
            $name = $typeConfig['name'];
            $class = 'Library\\Schema\\Resolver\\Input\\' . ucfirst($name);

            if (self::$container->has($class)) {
                $typeConfig['parseValue'] = fn (array $args) => (self::$container->make($class)->withArgs($args));
            }
        }

        return $typeConfig;
    }

    public static function resolveField($value, array $args, $context, ResolveInfo $info)
    {
        // Find the object for this
        $class = 'Library\\Schema\\Resolver\\' . $info->parentType->name . '\\' . ucfirst($info->fieldName);

        if (self::$container->has($class)) {
            $object = self::$container->get($class);
            if ($object instanceof QueryResolver || $object instanceof MutationResolver) {
                return self::$container->call([$object, 'resolve'], [$args, $context]);
            }
        }

        throw new Exception('Unknown GraphQL ' . $info->parentType->name . ' resolver `' . $class . '`');
    }

}