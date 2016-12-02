<?php
namespace Egb\UserBundle\EventListener;

use Egb\UserBundle\Controller\UserController;
use Egb\UserBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;


class UserTypeListener {
	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * UserTypeListener constructor.
	 *
	 * @param Container $container
	 */
	public function __construct(Container $container) {
		$this->container = $container;
	}

	protected function getClassMethod($classWithMethod) {
		return substr($classWithMethod, strpos($classWithMethod, '::') + 2);
	}

	/**
	 * @param GetResponseForControllerResultEvent $event A GetResponseForControllerResultEvent instance
	 */
	public function onKernelView(GetResponseForControllerResultEvent $event) {
		$request = $event->getRequest();
		$parameters = $event->getControllerResult();
	}

	/**
	 * @param GetResponseForControllerResultEvent $event A GetResponseForControllerResultEvent instance
	 */
	public function onKernelController(FilterControllerEvent $event) {
		$controller = $event->getController();
		$attributes = $event->getRequest()->attributes;
		if (isset($controller[0]) && ($controller[0] instanceof UserController) && ($controller[0]->getEntityClass() == 'UserBundle:User')) {
			/** @var UserRepository $repository */
			$repository = $controller[0]->getRepository();
			$controllerEntityClass = $repository->getEntityName();
			$id = $attributes->get('id');
			if ($id) {
				$user = $repository->find($id);
				$userEntityClass = get_class($user);
				// does the entity class from loaded controller (by url) match to the user's real entity class (by database)?
				if ($controllerEntityClass <> $userEntityClass) {
					// does'nt match. We will set the proper controller for the request.
					$parser = new Controller\ControllerNameParser($this->container->get('kernel'));
					$resolver = new Controller\ControllerResolver($this->container, $parser);
					$request = new Request();
					$request->attributes->set('_controller', str_replace('\\Entity\\', '\\Controller\\', $userEntityClass).'Controller::'.$controller[1]);
					$event->setController($resolver->getController($request));
				}
			}
		}
	}
}
