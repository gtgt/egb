<?php

namespace Egb\UserBundle\Controller;

use Egb\UserBundle\Entity;
use Egb\UserBundle\Form;

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
 * @Rest\RouteResource("Parent")
 * @Rest\Version("v1")
 */
class ParentController extends UserController {

	protected $entityClass = 'UserBundle:Paren';
	//protected $formTypeClass = Form\ParentType::class;
	protected $userType = 'parent';

}
