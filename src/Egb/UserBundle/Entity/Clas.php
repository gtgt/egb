<?php

namespace Egb\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Clas
 *
 * @ORM\Table(name="class")
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Clas {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="clid", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=3)
	 *
	 * @Serializer\Expose
	 */
	private $name;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 * @ORM\OneToOne(targetEntity="Teacher", mappedBy="class")
	 *
	 * @Serializer\Type("Egb\UserBundle\Entity\Teacher")
	 * @Serializer\Expose
	 */
	private $teacher;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\OneToMany(targetEntity="Student", mappedBy="class")
	 *
	 * @Serializer\Type("ArrayCollection<Egb\UserBundle\Entity\Student>")
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

