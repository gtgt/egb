<?php

namespace Egb\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Student
 *
 * @ORM\Table(name="teacher")
 * @ORM\Entity
 */
class Teacher extends User {
	/**
	 * Set discrimiator value.
	 * We cannot use Doctrine annotations, since it will see as a duplicate declaration.
	 */
	//protected $type = 'teacher';

	/**
	 * @var Clas
	 *
	 * @ORM\OneToOne(targetEntity="Clas", inversedBy="teacher")
	 * @ORM\JoinColumn(name="clid", referencedColumnName="clid", unique=true)
	 */
	private $class;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\OneToMany(targetEntity="Subject", mappedBy="teacher")
	 */
	private $subjects;

	public function __construct() {
		$this->subjects = new \Doctrine\Common\Collections\ArrayCollection();
	}
}

