<?php

namespace Egb\ClassBundle\Controller;

use Egb\ClassBundle\Entity;

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
 * Class controller
 *
 * @Rest\RouteResource("Subject")
 * @Rest\Version("v1")
 */
class ClassController extends Controller {

	protected $entityClass = 'ClassBundle:Clas';

	/**
	 * @return \Doctrine\Common\Persistence\ObjectRepository|\Egb\ClassBundle\Repository\ClassRepository
	 */
	public function getRepository() {
		return $this->getDoctrine()->getRepository($this->entityClass);
	}

	/**
	 * List all classs.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes = {
	 *     200 = "Returned when successful"
	 *   }
	 * )
	 *
	 * @Rest\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing classs.")
	 * @Rest\QueryParam(name="limit", requirements="\d+", default="5", description="How many classs to return.")
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

		return array('offset' => $offset, 'limit' => $limit, 'start' => $start, 'classs' => $this->getClassRepository()->findBy(array(), null, $limit, $offset));
	}

	/**
	 * Get a single class.
	 *
	 * @ApiDoc(
	 *   output = "Egb\ClassBundle\Model\Class",
	 *   statusCodes = {
	 *     200 = "Returned when successful",
	 *     404 = "Returned when the class is not found"
	 *   }
	 * )
	 *
	 * @Rest\View(templateVar="class", serializerEnableMaxDepthChecks=true, serializerGroups={"Default","Details"})
	 *
	 * @param int $id the class name
	 *
	 * @return Entity\Clas|object
	 * @throws NotFoundHttpException when class not exist
	 */
	public function getAction($id) {
		$class = $this->getClassRepository()->find($id);
		if (!is_object($class)) {
			throw $this->createNotFoundException();
		}
		return $class;
	}

}
