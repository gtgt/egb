<?php

namespace Egb\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Student
 *
 * @ORM\Table(name="parent")
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Paren extends User {

	/**
	 * Set discrimiator value.
	 * We cannot use Doctrine annotations, since it will see as a duplicate declaration.
	 */
	//protected $type = 'parent';

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\OneToMany(targetEntity="Student", mappedBy="parent")
	 *
	 * @Serializer\Type("ArrayCollection<Egb\UserBundle\Entity\Student>")
	 * @Serializer\Groups({"Me"})
	 * @Serializer\Expose
	 */
	private $students;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->students = new \Doctrine\Common\Collections\ArrayCollection();
	}
}

