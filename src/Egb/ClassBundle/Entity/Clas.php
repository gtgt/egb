<?php

namespace Egb\ClassBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * ClassEntity
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
	public $id;

	/**
	 * @ORM\Column(type="string", length=3)
	 * @Assert\Expression(expression="[1-8]/[A-F]", message="class.validation.wrongname")
	 *
	 * @Serializer\Expose
	 */
	public $name;

	/**
	 * @ORM\Column(type="integer", length=4)
	 * @Assert\GreaterThanOrEqual(value="2016")
	 * @Serializer\Expose
	 */
	public $year;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 * @ORM\OneToOne(targetEntity="Egb\UserBundle\Entity\Teacher", mappedBy="class")
	 *
	 * @Serializer\Type("Egb\UserBundle\Entity\Teacher")
	 * @Serializer\Expose
	 * @Serializer\MaxDepth(3)
	 */
	public $teacher;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\OneToMany(targetEntity="Egb\UserBundle\Entity\Student", mappedBy="class")
	 *
	 * @Serializer\Type("ArrayCollection<Egb\UserBundle\Entity\Student>")
	 * @Serializer\Expose
	 * @Serializer\MaxDepth(2)
	 */
	public $students;

}

