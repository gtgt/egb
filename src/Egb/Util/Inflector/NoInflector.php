<?php
namespace Egb\Util\Inflector;

use FOS\RestBundle\Inflector\InflectorInterface;

/**
 * Inflector class which does'nt pluralizes
 */
class NoInflector implements InflectorInterface
{
    public function pluralize($word)
    {
        // Don't pluralize
        return $word;
    }
}