<?php

namespace Osbekro\CommentsBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;

use Osbekro\CommentsBundle\Entity\Comment;
use Osbekro\CommentsBundle\Entity\CommentableObject;

class CommentManager
{
    protected $entityManager;

    public function __construct(EntityManager $manager)
    {
        $this->entityManager = $manager;
    }

    public function getCommentsFor($object)
    {
        $ref = $this
            ->entityManager
            ->getRepository('OsbekroCommentsBundle:CommentableObject')
            ->findOneBy(array(
                'class'=>get_class($object),
                'key'=>$object->getId()
                )
            );
        if ($ref === null) {
            return new ArrayCollection();
        }

        $comments = $this
            ->entityManager
            ->getRepository('OsbekroCommentsBundle:Comment')
            ->findBy(array('object'=>$ref));

        return $comments;
    }

    public function createCommentFor($object)
    {
        return new Comment($this->getThreadFor($object));
    }

    public function getThreadComments(CommentableObject $thread, $page, $perPage)
    {
        if ($page < 0) {
            $page = 0;
        }
        if ($perPage < 0) {
            $perPage = 0;
        }
        return $this
            ->entityManager
            ->getRepository('OsbekroCommentsBundle:Comment')
            ->findBy(
                array('object'=>$thread),
                array('created'=>'DESC'),
                $perPage,
                $page * $perPage
            );
    }

    public function getThreadFor($object)
    {
        $ref = $this
            ->entityManager
            ->getRepository('OsbekroCommentsBundle:CommentableObject')
            ->findOneBy(array(
                'class'=>get_class($object),
                'key'=>$object->getId()
                )
            );
        if ($ref === null) {
            $ref = new CommentableObject($object);
            $this->entityManager->persist($ref);
            $this->entityManager->flush();
        }
        return $ref;
    }

    public function countComments(CommentableObject $commentableObject)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->add('select', 'count(c.id)')
            ->add('from', 'OsbekroCommentsBundle:Comment c')
            ->add('where', 'c.object = :object')
            ->setParameter('object', $commentableObject->getId());
        return $qb->getQuery()->getSingleScalarResult();
    }
}