<?php

namespace Egb\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Subject
 *
 * @ORM\Table(name="subject")
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Subject {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="suid", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=128)
	 *
	 * @Serializer\Expose
	 */
	private $name;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="Student")
	 * @ORM\JoinTable(name="subject_students",
	 *   joinColumns={@ORM\JoinColumn(name="suid", referencedColumnName="suid")},
	 *   inverseJoinColumns={@ORM\JoinColumn(name="uid", referencedColumnName="uid")}
	 *  )
	 *
	 * @Serializer\Type("ArrayCollection<Egb\UserBundle\Entity\Student>")
	 * @Serializer\Expose
	 */
	private $students;

	/**
	 * @var Teacher
	 *
	 * @ORM\ManyToOne(targetEntity="Teacher", inversedBy="subjects")
	 * @ORM\JoinColumn(name="uid", referencedColumnName="uid")
	 *
	 * @Serializer\Type("Egb\UserBundle\Entity\Teacher")
	 * @Serializer\Expose
	 */
	private $teacher;


	/**
	 * Constructor
	 */
	public function __construct() {
		$this->students = new \Doctrine\Common\Collections\ArrayCollection();
	}

}

