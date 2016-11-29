<?php

namespace Egb\SubjectBundle\Controller;

use Egb\SubjectBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

class SubjectController extends Controller {

	private $entityClass = 'SubjectBundle:Subject';

	/**
	 * @return \Doctrine\Common\Persistence\ObjectRepository|\Egb\SubjectBundle\Repository\SubjectRepository
	 */
	public function getSubjectRepository() {
		return $this->getDoctrine()->getRepository($this->entityClass);
	}

	/**
	 * List all subjects.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes = {
	 *     200 = "Returned when successful"
	 *   }
	 * )
	 *
	 * @QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing subjects.")
	 * @QueryParam(name="limit", requirements="\d+", default="5", description="How many subjects to return.")
	 *
	 * @View()
	 *
	 * @param ParamFetcherInterface $paramFetcher param fetcher service
	 *
	 * @return array
	 */
	public function getSubjectsAction(ParamFetcherInterface $paramFetcher) {
		$offset = (int)$paramFetcher->get('offset');
		$start = null == $offset ? 0 : $offset + 1;
		$limit = $paramFetcher->get('limit');

		return array('offset' => $offset, 'limit' => $limit, 'start' => $start, 'subjects' => $this->getSubjectRepository()->findBy(array(), null, $limit, $offset));
	}

	/**
	 * Get a single subject.
	 *
	 * @ApiDoc(
	 *   output = "Egb\SubjectBundle\Model\Subject",
	 *   statusCodes = {
	 *     200 = "Returned when successful",
	 *     404 = "Returned when the subject is not found"
	 *   }
	 * )
	 *
	 * @View(templateVar="subject", serializerGroups={"Default","Details"})
	 *
	 * @param int $id the subject name
	 *
	 * @return Entity\Subject|object
	 * @throws NotFoundHttpException when subject not exist
	 */
	public function getSubjectAction($id) {
		$subject = $this->getSubjectRepository()->find($id);
		if (!is_object($subject)) {
			throw $this->createNotFoundException();
		}
		return $subject;
	}

}
