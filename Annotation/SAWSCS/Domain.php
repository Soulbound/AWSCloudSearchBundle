<?php

namespace SAWSCS\Annotation\SAWSCS;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Domain
{
    private $name;

    public function __construct($options)
    {
        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->$key = $value;
        }
    }

    public function getName()
    {
        return $this->name;
    }
}