<?php

namespace Egb\UserBundle\Controller;

use Egb\UserBundle\Entity;
use Egb\UserBundle\Form;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Request\ParamFetcherInterface;

use FOS\RestBundle\Controller\Annotations as Rest;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Teacher controller (extends user controller)
 *
 * @Rest\RouteResource("Teacher")
 * @Rest\Version("v1")
 */
class TeacherController extends UserController {

	protected $entityClass = 'UserBundle:Teacher';
	protected $formTypeClass = Form\TeacherType::class;
	protected $userType = 'teacher';

	/**
	 * Get consulting hours
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes = {
	 *     200 = "Returned when successful"
	 *   }
	 * )
	 *
	 * @Rest\View(
	 *   templateVar="consultingHours"
	 *   serializerEnableMaxDepthChecks=true
	 * )
	 *
	 * @return ArrayCollection
	 */
	public function getConsultinghoursAction($id) {
		$teacher = $this->getAction($id);
		return $teacher->consultingHours;
	}

}
