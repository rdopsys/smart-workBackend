<?php

namespace TotalPollVendors\League\Container\Argument;
! defined( 'ABSPATH' ) && exit();


use TotalPollVendors\League\Container\ImmutableContainerAwareInterface;
use ReflectionFunctionAbstract;

interface ArgumentResolverInterface extends ImmutableContainerAwareInterface
{
    /**
     * Resolve an array of arguments to their concrete implementations.
     *
     * @param  array $arguments
     * @return array
     */
    public function resolveArguments(array $arguments);

    /**
     * Resolves the correct arguments to be passed to a method.
     *
     * @param  \ReflectionFunctionAbstract $method
     * @param  array                       $args
     * @return array
     */
    public function reflectArguments(ReflectionFunctionAbstract $method, array $args = []);
}
