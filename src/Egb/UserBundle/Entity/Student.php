<?php

namespace Egb\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Student
 *
 * @ORM\Entity(repositoryClass="Egb\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="student")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Student extends User {
	/**
	 * Provide discrimiator value.
	 * We cannot use Doctrine annotations, since it will see as a duplicate declaration.
	 */
	protected $userType = 'student';

	/**
	 * @ORM\ManyToOne(targetEntity="Paren", inversedBy="students")
	 * @ORM\JoinColumn(name="parent_uid", referencedColumnName="uid", unique=true)
	 *
	 * @Serializer\Type("Egb\UserBundle\Entity\Paren")
	 * @Serializer\Groups({"Default", "Me"})
	 * @Serializer\Expose
	 * @Serializer\MaxDepth(1)
	 */
	private $parent;

	/**
	 * @var \Egb\ClassBundle\Entity\Clas
	 *
	 * @ORM\ManyToOne(targetEntity="Egb\ClassBundle\Entity\Clas", inversedBy="students")
	 * @ORM\JoinColumn(name="clid", referencedColumnName="clid", unique=true)
	 *
	 * @Serializer\Type("Egb\ClassBundle\Entity\Clas")
	 * @Serializer\Groups({"Default", "Detail", "Me"})
	 * @Serializer\Expose
	 * @Serializer\MaxDepth(2)
	 */
	private $class;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="Egb\SubjectBundle\Entity\Subject", mappedBy="students")
	 *
	 * @Serializer\Type("ArrayCollection<Egb\SubjectBundle\Entity\Subject>")
	 * @Serializer\Groups({"Default", "Detail", "Me"})
	 * @Serializer\Expose
	 * @Serializer\MaxDepth(1)
	 */
	private $subjects;

}

