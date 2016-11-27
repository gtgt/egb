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
 * @ORM\Table(name="student")
 * @ORM\Entity
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
	 * @Expose
	 */
	private $parent;

	/**
	 * @var Clas
	 *
	 * @ORM\ManyToOne(targetEntity="Clas", inversedBy="students")
	 * @ORM\JoinColumn(name="clid", referencedColumnName="clid")
	 * @Expose
	 */
	private $class;


}

