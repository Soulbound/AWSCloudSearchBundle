<?php

namespace SAWSCS\Annotation\SAWSCS;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Index
{
    private $name = "";
    private $type = "text";
    private $default = "";

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

    public function getType()
    {
        return $this->type;
    }

    public function getDefault()
    {
        return $this->default;
    }
}