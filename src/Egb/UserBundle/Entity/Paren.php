<?php

namespace Egb\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Student
 *
 * @ORM\Entity(repositoryClass="Egb\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="parent")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Paren extends User {
	/**
	 * Provide discrimiator value.
	 * We cannot use Doctrine annotations, since it will see as a duplicate declaration.
	 */
	protected $userType = 'parent';

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\OneToMany(targetEntity="Student", mappedBy="parent")
	 *
	 * @Serializer\Type("ArrayCollection<Egb\UserBundle\Entity\Student>")
	 * @Serializer\Groups({"Default", "Me"})
	 * @Serializer\Expose
	 * @Serializer\MaxDepth(1)
	 */
	protected $students;
}

