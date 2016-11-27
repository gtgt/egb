<?php

namespace Egb\UserBundle\Controller;

use Egb\UserBundle\Form;
use Egb\UserBundle\Exception;
use Egb\UserBundle\Entity;

use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Request\ParamFetcherInterface;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Version;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class UserController
 *
 * @Version("v1")
 */
class UserController extends FOSRestController {

	private $entityClass = 'UserBundle:User';

	/**
	 * @return \Doctrine\Common\Persistence\ObjectRepository|\Egb\UserBundle\Repository\UserRepository
	 */
	public function getUserRepository() {
		return $this->getDoctrine()->getRepository($this->entityClass);
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
	private function processForm(Entity\User $user, array $parameters, $method = "PUT") {
		$form = $this->formFactory->create(new Form\UserType(), $user, array('method' => $method));
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
	 * @View(
	 *  templateVar = "form"
	 * )
	 *
	 * @return \Symfony\Component\Form\Form
	 */
	public function newUsersAction() {
		return $this->createForm(Form\UserType::class);
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
	 * @QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing users.")
	 * @QueryParam(name="limit", requirements="\d+", default="5", description="How many users to return.")
	 *
	 * @View()
	 *
	 * @param ParamFetcherInterface $paramFetcher param fetcher service
	 *
	 * @return array
	 */
	public function getUsersAction(ParamFetcherInterface $paramFetcher) {
		$offset = (int)$paramFetcher->get('offset');
		$start = null == $offset ? 0 : $offset + 1;
		$limit = $paramFetcher->get('limit');

		return array('offset' => $offset, 'limit' => $limit, 'start' => $start, 'users' => $this->getUserRepository()->findBy(array(), null, $limit, $offset));
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
	 * @View(templateVar="user", serializerGroups={"Default","Details"})
	 *
	 * @param int $id the user name
	 *
	 * @return Entity\User|object
	 * @throws NotFoundHttpException when user not exist
	 */
	public function getUserAction($id) {
		$user = $this->getUserRepository()->find($id);
		if (!is_object($user)) {
			throw $this->createNotFoundException();
		}
		return $user;
	}

	/**
	 *
	 * @View(serializerGroups={"Default","Me","Details"})
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
	 * List all users.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes = {
	 *     200 = "Returned when successful"
	 *   }
	 * )
	 *
	 * @QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing users.")
	 * @QueryParam(name="limit", requirements="\d+", default="5", description="How many users to return.")
	 *
	 * @View()
	 *
	 * @param ParamFetcherInterface $paramFetcher param fetcher service
	 *
	 * @return array
	 */
	public function getStudentsAction($id) {
		return $this->getUserAction($id);
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
	 * @View(templateVar="user")
	 *
	 * @param int $id the user id
	 * @return \Symfony\Component\Form\Form
	 */
	public function editUserAction($id) {
		$user = $this->getUserRepository()->find($id);
		if (false === $user) {
			throw $this->createNotFoundException("User does not exist.");
		}

		return $this->createForm(Form\UserType::class, $user);
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
	 * @View(
	 *  template = "UserBundle:User:newUser.html.twig",
	 *  statusCode = Response::HTTP_BAD_REQUEST,
	 *  templateVar = "form"
	 * )
	 *
	 * @param Request $request the request object
	 *
	 * @return FormTypeInterface|View
	 */
	public function postUserAction(Request $request) {
		try {
			$newUser = $this->processForm(new $this->entityClass(), $request->request->all(), 'POST');
			return $this->routeRedirectView('api_get_user', array('id' => $newUser->getId(), '_format' => $request->get('_format')), Response::HTTP_CREATED);
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
	 * @View(
	 *  template = "UserBundle:User:editUser.html.twig",
	 *  templateVar = "form"
	 * )
	 *
	 * @param Request $request the request object
	 * @param int $id the user id
	 *
	 * @return FormTypeInterface|View
	 *
	 * @throws NotFoundHttpException when user not exist
	 */
	public function putUserAction(Request $request, $id) {
		try {
			if (!($user = $this->getUserRepository()->find($id))) {
				$statusCode = Response::HTTP_CREATED;
				$user = $this->processForm(new $this->entityClass(), $request->request->all(), 'POST');
			} else {
				$statusCode = Response::HTTP_NO_CONTENT;
				$user = $this->processForm($user, $request->request->all(), 'PUT');
			}
			return $this->routeRedirectView('api_get_user', array('id' => $user->getId(), '_format' => $request->get('_format')), $statusCode);
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
	 * @View(
	 *  template = "UserBundle:User:editUser.html.twig",
	 *  templateVar = "form"
	 * )
	 *
	 * @param Request $request the request object
	 * @param int $id the user id
	 *
	 * @return FormTypeInterface|View
	 *
	 * @throws NotFoundHttpException when user not exist
	 */
	public function patchUserAction(Request $request, $id) {
		try {
			$user = $this->processForm($this->getUserRepository()->find($id), $request->request->all(), 'PATCH');
			return $this->routeRedirectView('api_get_user', array('id' => $user->getId(), '_format' => $request->get('_format')), Response::HTTP_NO_CONTENT);
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
	 * @return View
	 */
	public function deleteUserAction($id) {
		$this->getUserManager()->remove($id);

		// There is a debate if this should be a 404 or a 204
		// see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
		return $this->routeRedirectView('get_users', array(), Response::HTTP_NO_CONTENT);
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
	 * @return View
	 */
	public function removeUserAction($id) {
		return $this->deleteUsersAction($id);
	}
}
