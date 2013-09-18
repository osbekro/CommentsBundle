<?php

namespace Osbekro\CommentsBundle\Entity;

class CommentableObject
{
    protected $id;

    protected $class;

    protected $key;

    public function __construct($object = null)
    {
        if ($object !== null) {
            $this->class = get_class($object);
            $this->key = $object->getId();
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }
}
