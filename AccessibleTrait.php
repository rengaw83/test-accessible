<?php

declare(strict_types=1);

namespace R83Dev\TestAccessible;

/**
 * Accessible trait.
 *
 * A helper to call inaccessible (protected or private) methods, properties and constants in tests.
 *
 * @author Michael Wagner
 */
trait AccessibleTrait
{
    /**
     * Helper function to call an inaccessible method.
     */
    protected function callInaccessibleMethod(object $object, string $name): mixed
    {
        $arguments = func_get_args();
        array_splice($arguments, 0, 2);

        $reflectionObject = new \ReflectionObject($object);
        $reflectionMethod = $reflectionObject->getMethod($name);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invokeArgs($object, $arguments);
    }

    /**
     * Helper function to get an inaccessible property.
     */
    protected function getInaccessibleProperty(object $object, string $property): mixed
    {
        $refObject = new \ReflectionObject($object);
        $refProperty = $refObject->getProperty($property);
        $refProperty->setAccessible(true);

        return $refProperty->getValue($object);
    }

    /**
     * Helper function to set an inaccessible property.
     *
     * @template T
     *
     * @param T $object
     *
     * @return T
     */
    protected function setInaccessibleProperty(object $object, string $property, mixed $value = null): object
    {
        $refObject = new \ReflectionObject($object);
        $refProperty = $refObject->getProperty($property);
        $refProperty->setAccessible(true);
        $refProperty->setValue($object, $value);

        return $object;
    }

    /**
     * Helper function to get an inaccessible constant.
     */
    protected function getInaccessibleConstant(object $object, string $constant): mixed
    {
        $refObject = new \ReflectionObject($object);
        $refProperty = $refObject->getReflectionConstant($constant);

        return $refProperty->getValue();
    }
}
