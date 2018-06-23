<?php

namespace AppBundle\Controller;
use AppBundle\Entity\User;
use AppBundle\Form\FilterType\Model\ListFilter;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/user")
 */
class UserController extends AbstractApiController
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("")
     * @Method("GET")
     */
    public function cgetAction(Request $request)
    {
        $filter = new ListFilter();
        $form = $this->createForm('AppBundle\Form\FilterType\ListFilterType', $filter, ['method' => 'GET']);
        $form->handleRequest($request);

        if ($this->getErrors($form)) {
            return $this->returnViewResponse($this->getErrors($form), Response::HTTP_BAD_REQUEST, $filter->getSerialisationGroups());
        }

        /** @var AbstractPagination $pagination */
        $pagination = $this->get('knp_paginator')->paginate(
            $this
                ->getDoctrine()
                ->getRepository(User::class)
                ->filterAndReturnQuery($filter),
            $filter->getPage(),
            $filter->getLimit()
        );

        return $this->returnCollectionViewResponse(
            $pagination,
            Response::HTTP_OK,
            $filter->getSerialisationGroups()
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("")
     * @Method("POST")
     */
    public function postAction(Request $request)
    {
        $job = new User();
        $form = $this->createForm('AppBundle\Form\Type\UserType', $job, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();

            return $this->returnViewResponse($job, Response::HTTP_CREATED);
        }

        return $this->returnViewResponse($this->getErrors($form), Response::HTTP_BAD_REQUEST);
    }
}