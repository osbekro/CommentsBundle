<?php

namespace Osbekro\CommentsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ThreadController extends Controller
{
    public function reloadAction(Request $request, $id, $page = 0, $perPage = 3)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $thread = $em
            ->getRepository('OsbekroCommentsBundle:CommentableObject')
            ->findOneBy(array('id'=>$id));
        $comments = $this
            ->get('osbekro.comments.manager')
            ->getThreadComments($thread, $page, $perPage);

        $numberOfComments = $this->get('osbekro.comments.manager')->countComments($thread);

        return $this->render('OsbekroCommentsBundle:Thread:view.html.twig', array(
                'comments' => $comments,
                'thread' => $thread,
                'nodiv'=>$request->get('nodiv', 0),
                'perPage'=>$perPage,
                'numberOfComments' => $numberOfComments,
        ));
    }

    public function viewAction(Request $request, $object, $page = 0, $perPage = 3)
    {
        $thread = $this->get('osbekro.comments.manager')->getThreadFor($object);
        $comments = $this->get('osbekro.comments.manager')->getThreadComments($thread, $page, $perPage);
        $numberOfComments = $this->get('osbekro.comments.manager')->countComments($thread);
        return $this->render('OsbekroCommentsBundle:Thread:view.html.twig', array(
                'comments' => $comments,
                'thread' => $thread,
                'nodiv'=>$request->get('nodiv', 0),
                'perPage'=>$perPage,
                'numberOfComments' => $numberOfComments
            ));
    }

    public function postAction(Request $request)
    {
        
        if (!$this->get('security.context')->isGranted('COMMENTS_MODERATOR')) {
            throw new AccessDeniedException();
        }
        $thread = new Thread();
        $em = $this->getDoctrine()->getManager();
        $em->persist($thread);
        $em->flush($thread);
        return new Response(null, 201);
    }

    public function deleteAction(Thread $thread)
    {
        if ($this->get('security.context')->isGranted('DELETE', $thread)) {
            return new Response();
        }
        throw new AccessDeniedException();
    }

    public function editAction(Thread $thread)
    {
        if ($this->get('security.context')->isGranted('EDIT', $thread)) {
            return new Response();
        }
        throw new AccessDeniedException();
    }
}
