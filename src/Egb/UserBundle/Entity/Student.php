<?php

namespace Egb\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Student extends User {

	/**
	 * Set discrimiator value.
	 * We cannot use Doctrine annotations, since it will see as a duplicate declaration.
	 */
	//protected $type = 'student';

	/**
	 * @ORM\ManyToOne(targetEntity="Paren", inversedBy="students")
	 * @ORM\JoinColumn(name="parent_uid", referencedColumnName="uid")
	 *
	 * @Serializer\Type("Egb\UserBundle\Entity\Paren")
	 * @Serializer\Groups({"Default", "Me"})
	 * @Serializer\Expose
	 */
	private $parent;

	/**
	 * @var \Egb\ClassBundle\Entity\Clas
	 *
	 * @ORM\ManyToOne(targetEntity="Egb\ClassBundle\Entity\Clas", inversedBy="students")
	 * @ORM\JoinColumn(name="clid", referencedColumnName="clid")
	 *
	 * @Serializer\Type("Egb\ClassBundle\Entity\Clas")
	 * @Serializer\Groups({"Default", "Me"})
	 * @Serializer\Expose
	 */
	private $class;


}

