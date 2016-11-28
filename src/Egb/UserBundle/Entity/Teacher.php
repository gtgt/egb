<?php

namespace Egb\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Student
 *
 * @ORM\Table(name="teacher")
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
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
	 *
	 * @Serializer\Type("Egb\UserBundle\Entity\Clas")
	 * @Serializer\Groups({"Me"})
	 * @Serializer\Expose
	 */
	private $class;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\OneToMany(targetEntity="Subject", mappedBy="teacher")
	 *
	 * @Serializer\Type("ArrayCollection<Egb\UserBundle\Entity\Subject>")
	 * @Serializer\Groups({"Me"})
	 * @Serializer\Expose
	 * @Serializer\MaxDepth(1)
	 */
	private $subjects;

	public function __construct() {
		$this->subjects = new \Doctrine\Common\Collections\ArrayCollection();
	}
}

