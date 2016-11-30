<?php

namespace Egb\UserBundle\Controller;

use Egb\UserBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Request\ParamFetcherInterface;

use FOS\RestBundle\Controller\Annotations as Rest;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Student controller (extends user controller)
 *
 * @Rest\RouteResource("Student")
 * @Rest\Version("v1")
 */
class StudentController extends UserController {

	protected $entityClass = 'UserBundle:Student';
}
