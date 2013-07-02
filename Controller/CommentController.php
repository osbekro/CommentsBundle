<?php

namespace Osbekro\CommentsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Osbekro\CommentsBundle\Entity\Comment;
use Osbekro\CommentsBundle\Form\CommentType;

class CommentController extends Controller
{
    public function viewAction(Comment $comment)
    {
        return $this->render('OsbekroCommentsBundle:Comment:view.html.twig', array('comment'=>$comment));
    }

    public function formAction(Request $request, $object)
    {
        $comment = $this->get('osbekro.comments.manager')->createCommentFor($object);
        $form = $this->createForm(new CommentType(), $comment);
        return $this
            ->render(
                'OsbekroCommentsBundle:Comment:form.html.twig',
                array('form'=>$form->createView())
            );
    }

    public function createAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $obj = $em
            ->getRepository('OsbekroCommentsBundle:CommentableObject')
            ->findOneBy(array('id'=>$id));

        $comment = new Comment($obj);
        $form = $this->createForm(new CommentType(), $comment);
        $form->bind($request);
        if ($form->isValid()) {
            //TODO Check if anonymous is available
            $user= $this->get('security.context')->getToken()->getUser();
            $comment->setAuthor($user);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($comment);
            $em->flush();
            if ($request->isXmlHttpRequest()) {
                $type = $request->get('_type');
                if ($type === 'html') {
                    return $this->render(
                        'OsbekroCommentsBundle:Comment:view.html.twig',
                        array('comment' => $comment)
                    );
                }
                return $this->get('serializer')->serialize($comment, $type);
            }
            $this->get('session')->getFlashBag()->add('notice', 'Comment have been posted successfuly');
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        return new Response(json_encode($errors), 400);
    }


    public function deleteAction(Comment $comment)
    {
        if ($this->get('security.context')->isGranted('DELETE', $comment)) {
            return new Response();
        }
        throw new AccessDeniedException();
    }

    public function editAction(Comment $comment)
    {
        if (!$this->get('security.context')->isGranted('EDIT', $comment)) {
            throw new AccessDeniedException();
        }

    }

    public function voteAction(Comment $comment)
    {
        if ($this->get('security.context')->isGranted('VOTE', $comment)) {
            return new Response();
        }
        throw new AccessDeniedException();
    }
}
