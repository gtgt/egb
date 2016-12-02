<?php
namespace Egb\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Student
 *
 * @ORM\Entity(repositoryClass="Egb\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="teacher")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Teacher extends User {
	/**
	 * Provide discrimiator value.
	 * We cannot use Doctrine annotations, since it will see as a duplicate declaration.
	 */
	protected $userType = 'teacher';

	/**
	 * @var \Egb\ClassBundle\Entity\Clas
	 *
	 * @ORM\OneToOne(targetEntity="Egb\ClassBundle\Entity\Clas", inversedBy="teacher")
	 * @ORM\JoinColumn(name="clid", referencedColumnName="clid", unique=true)
	 *
	 * @Serializer\Type("Egb\ClassBundle\Entity\Clas")
	 * @Serializer\Groups({"Default", "Detail"})
	 * @Serializer\Expose
	 */
	private $class;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\OneToMany(targetEntity="Egb\SubjectBundle\Entity\Subject", mappedBy="teacher")
	 *
	 * @Serializer\Type("ArrayCollection<Egb\SubjectBundle\Entity\Subject>")
	 * @Serializer\Groups({"Default", "Detail"})
	 * @Serializer\Expose
	 * @Serializer\MaxDepth(2)
	 */
	private $subjects;

}

