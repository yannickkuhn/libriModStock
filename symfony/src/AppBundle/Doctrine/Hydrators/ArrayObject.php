<?php

namespace AppBundle\Doctrine\Hydrators;

use Doctrine\Common\Util\Inflector;

/**
 * Allow to access array properties using getters/setters.
 *
 * @method null set${Property}(mixed $value) set array property
 * @method mixed get${Property}() get array property
 *
 * @author Bernard Baltrusaitis <bernard@runawaylover.info>
 */
class ArrayObject extends \ArrayObject
{
    public function __construct($array)
    {
        parent::__construct($array, self::ARRAY_AS_PROPS);
    }

    /**
     * Magic method catches getters and setters.
     *
     * @param string $name      property name
     * @param array  $arguments property value
     *
     * @return mixed the value at the specified index or NULL
     *
     * @throws \InvalidArgumentException
     * @throws \BadMethodCallException
     */
    public function __call($name, $arguments)
    {
        $matches = null;

        if (preg_match('/^get(\w+)/', $name, $matches)) {
            $property = Inflector::tableize($matches[1]);

            if (0 !== sizeof($arguments)) {
                throw new \InvalidArgumentException(
                    sprintf('The method `%s` does not expect arguments, %d given',
                        $name, sizeof($arguments)));
            }

            return $this->offsetGet($property);
        }

        if (preg_match('/^set(\w+)/', $name, $matches)) {
            $property = Inflector::tableize($matches[1]);

            if (1 !== sizeof($arguments)) {
                throw new \InvalidArgumentException(
                    sprintf('The method `%s` expects exactly 1 argument, %d given',
                        $name, sizeof($arguments)));
            }

            return $this->offsetSet($property, current($arguments));
        }

        throw new \BadMethodCallException(
            sprintf('Class `%s` does not have `%s` method', __CLASS__, $name));
    }

    /**
     * Get the string representation of the current array.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->getArrayCopy());
    }
}
