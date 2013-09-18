<?php

namespace Osbekro\CommentsBundle\Entity;


/**
 * Comment
 */
class Comment
{
    /**
     * @var integer
     */
    protected $id;

    protected $body;

    protected $author;

    protected $object;

    protected $created;

    protected $updated;

    protected $authorName;


    public function __construct(CommentableObject $object = null)
    {
        if ($object !== null) {
            $this->setObject($object);
        }
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setAuthor($author = null)
    {
        $this->author = $author;
        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function setObject(CommentableObject $object)
    {
        $this->object = $object;
        return $this;
    }

    public function getAuthorName()
    {
        if ($this->author !== null) {
            $this->authorName = $this->author->__toString();
        } else {
            $this->authorName = 'Account have been deleted';
        }
        return $this->authorName;
    }

    public function setAuthorName($name)
    {
        $this->authorName = $name;
        return $this;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }
}
