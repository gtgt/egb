<?php

namespace Egb\UserBundle\Controller;

use Egb\UserBundle\Form;
use Egb\UserBundle\Exception;
use Egb\UserBundle\Entity;

use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Request\ParamFetcherInterface;

use FOS\RestBundle\Controller\Annotations as Rest;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * User controller
 *
 * @Rest\RouteResource("User")
 * @Rest\Version("v1")
 */
class UserController extends FOSRestController {

	protected $entityClass = 'UserBundle:User';
	protected $userType = 'user';

	/**
	 * @return \Doctrine\Common\Persistence\ObjectRepository|\Egb\UserBundle\Repository\UserRepository
	 */
	public function getRepository() {
		return $this->getDoctrine()->getRepository($this->entityClass);
	}


	/**
	 * Get a Form instance.
	 *
	 * @param Entity\User|null $user
	 * @param array $options
	 * @param string|null $routeName
	 * @return Form\UserType|\Symfony\Component\Form\Form
	 */
	protected function getForm($user = null, $options = array(), $actionRouteName = null) {
		if (null !== $actionRouteName) {
			$actionRouterParameters = array();
			if (is_array($actionRouteName)) {
				if (isset($actionRouteName[1])) $actionRouterParameters = $actionRouteName[1];
				$actionRouteName = array_shift($actionRouteName);
			} elseif ($user) {
				$actionRouterParameters['id'] = $user->id;
			}
			$options['action'] = $this->generateUrl($actionRouteName, $actionRouterParameters);
		}
		if (null === $user) $user = new $this->entityClass();
		return $this->createForm(Form\UserType::class, $user, $options);
	}

	/**
	 * Processes the form.
	 *
	 * @param Entity\User $user
	 * @param array $parameters
	 * @param String $method
	 *
	 *
	 * @return Entity\User
	 * @throws \Egb\UserBundle\Exception\InvalidFormException
	 */
	protected function processForm($user = null, array $parameters, $method = "PUT") {
		$form = $this->getForm($user, array('method' => $method));
		$form->submit($parameters, 'PATCH' !== $method);
		if ($form->isValid()) {

			$user = $form->getData();
			$this->om->persist($user);
			$this->om->flush($user);

			return $user;
		}

		throw new Exception\InvalidFormException('Invalid submitted data', $form);
	}

	/**
	 * Presents the form to use to create a new user.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes = {
	 *     200 = "Returned when successful"
	 *   }
	 * )
	 *
	 * @Rest\View(
	 *  templateVar = "form"
	 * )
	 *
	 * @return FormTypeInterface|Rest\View
	 */
	public function newAction() {
		return $this->getForm(null, array(), 'api_post_user');
	}

	/**
	 * Presents the form to use to update an existing user.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes={
	 *     200 = "Returned when successful",
	 *     404 = "Returned when the user is not found"
	 *   }
	 * )
	 *
	 * @Rest\View(templateVar="user")
	 *
	 * @param int $id the user id
	 * @return \Symfony\Component\Form\Form
	 */
	public function editAction($id) {
		$user = $this->getRepository()->find($id);
		if (false === $user) {
			throw $this->createNotFoundException("User does not exist.");
		}

		return $this->getForm($user, array(), 'api_patch_user');
	}

	/**
	 * List all users.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes = {
	 *     200 = "Returned when successful"
	 *   }
	 * )
	 *
	 * @Rest\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing users.")
	 * @Rest\QueryParam(name="limit", requirements="\d+", default="5", description="How many users to return.")
	 *
	 * @Rest\View(serializerEnableMaxDepthChecks=true)
	 *
	 * @param ParamFetcherInterface $paramFetcher param fetcher service
	 *
	 * @return array
	 */
	public function getListAction(ParamFetcherInterface $paramFetcher) {
		$offset = (int)$paramFetcher->get('offset');
		$start = null == $offset ? 0 : $offset + 1;
		$limit = $paramFetcher->get('limit');

		return array('offset' => $offset, 'limit' => $limit, 'start' => $start, 'users' => $this->getRepository()->findBy(array(), null, $limit, $offset));
	}

	/**
	 * Get a single user.
	 *
	 * @ApiDoc(
	 *   output = "Egb\UserBundle\Model\User",
	 *   statusCodes = {
	 *     200 = "Returned when successful",
	 *     404 = "Returned when the user is not found"
	 *   }
	 * )
	 *
	 * @Rest\View(templateVar="user", serializerEnableMaxDepthChecks=true, serializerGroups={"Default","Details"})
	 *
	 * @param int $id the user name
	 *
	 * @return Entity\User|object
	 * @throws NotFoundHttpException when user not exist
	 */
	public function getAction($id) {
		$user = $this->getRepository()->find($id);
		if (!is_object($user)) {
			throw $this->createNotFoundException();
		}
		return $user;
	}

	/**
	 *
	 * @Rest\View(serializerGroups={"Default","Me","Details"})
	 */
	public function getMeAction() {
		$this->forwardIfNotAuthenticated();
		return $this->getUser();
	}

	/**
	 * Shortcut to throw a AccessDeniedHttpException($message) if the user is not authenticated
	 * @param String $message The message to display (default:'warn.user.notAuthenticated')
	 */
	protected function forwardIfNotAuthenticated($message = 'warn.user.notAuthenticated') {
		if (!is_object($this->getUser())) {
			throw new AccessDeniedHttpException($message);
		}
	}

	/**
	 * Create a User from the submitted data.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   description = "Creates a new user from the submitted data.",
	 *   input = "Egb\UserBundle\Form\UserType",
	 *   statusCodes = {
	 *     200 = "Returned when successful",
	 *     400 = "Returned when the form has errors"
	 *   }
	 * )
	 *
	 * @Rest\View(
	 *  template = "UserBundle:User:new.html.twig",
	 *  statusCode = Response::HTTP_BAD_REQUEST,
	 *  templateVar = "form"
	 * )
	 *
	 * @param Request $request the request object
	 *
	 * @return FormTypeInterface|Rest\View
	 */
	public function postAction(Request $request) {
		try {
			$newUser = $this->processForm(null, $request->request->all(), 'POST');
			return $this->routeRedirectView('api_get_user', array('id' => $newUser), Response::HTTP_CREATED);
		} catch (Exception\InvalidFormException $exception) {
			return $exception->getForm();
		}
	}

	/**
	 * Update existing user from the submitted data or create a new user at a specific location.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   input = "Egb\UserBundle\Form\UserType",
	 *   statusCodes = {
	 *     201 = "Returned when the User is created",
	 *     204 = "Returned when successful",
	 *     400 = "Returned when the form has errors"
	 *   }
	 * )
	 *
	 * @Rest\View(
	 *  template = "UserBundle:User:editUser.html.twig",
	 *  templateVar = "form"
	 * )
	 *
	 * @param Request $request the request object
	 * @param int $id the user id
	 *
	 * @return FormTypeInterface|Rest\View
	 *
	 * @throws NotFoundHttpException when user not exist
	 */
	public function putAction(Request $request, $id) {
		try {
			if (!($user = $this->getRepository()->find($id))) {
				$statusCode = Response::HTTP_CREATED;
				$user = $this->processForm(null, $request->request->all(), 'POST');
			} else {
				$statusCode = Response::HTTP_NO_CONTENT;
				$user = $this->processForm($user, $request->request->all(), 'PUT');
			}
			return $this->routeRedirectView('api_get_user', array('user' => $user), $statusCode);
		} catch (Exception\InvalidFormException $exception) {
			return $exception->getForm();
		}
	}

	/**
	 * Update existing user from the submitted data or create a new user at a specific location.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   input = "Egb\UserBundle\Form\UserType",
	 *   statusCodes = {
	 *     204 = "Returned when successful",
	 *     400 = "Returned when the form has errors"
	 *   }
	 * )
	 *
	 * @Rest\View(
	 *  template = "UserBundle:User:edit.html.twig",
	 *  templateVar = "form"
	 * )
	 *
	 * @param Request $request the request object
	 * @param int $id the user id
	 *
	 * @return FormTypeInterface|Rest\View
	 *
	 * @throws NotFoundHttpException when user not exist
	 */
	public function patchAction(Request $request, $id) {
		try {
			$user = $this->processForm($this->getRepository()->find($id), $request->request->all(), 'PATCH');
			return $this->routeRedirectView('api_get_user', array('user' => $user), Response::HTTP_NO_CONTENT);
		} catch (Exception\InvalidFormException $exception) {
			return $exception->getForm();
		}
	}

	/**
	 * Removes a user.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes={
	 *     204="Returned when successful"
	 *   }
	 * )
	 *
	 * @param int $id the user id
	 *
	 * @return FormTypeInterface|Rest\View
	 */
	public function deleteAction($id) {
		$this->getUserManager()->remove($id);

		// There is a debate if this should be a 404 or a 204
		// see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
		return $this->routeRedirectView('get_user_list', array(), Response::HTTP_NO_CONTENT);
	}

	/**
	 * Removes a user.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes={
	 *     204="Returned when successful"
	 *   }
	 * )
	 *
	 * @param int $id the user id
	 *
	 * @return FormTypeInterface|Rest\View
	 */
	public function removeAction($id) {
		return $this->deleteAction($id);
	}
}
