<?php

namespace Egb\SubjectBundle\Entity;

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
	 * @var \Egb\UserBundle\Entity\Teacher
	 *
	 * @ORM\ManyToOne(targetEntity="Egb\UserBundle\Entity\Teacher", inversedBy="subjects")
	 * @ORM\JoinColumn(name="uid", referencedColumnName="uid")
	 *
	 * @Serializer\Type("Egb\UserBundle\Entity\Teacher")
	 * @Serializer\Expose
	 * @Serializer\MaxDepth(1)
	 */
	private $teacher;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="Egb\UserBundle\Entity\Student")
	 * @ORM\JoinTable(name="subject_students",
	 *   joinColumns={
	 *     @ORM\JoinColumn(name="suid", referencedColumnName="suid")
	 *   },
	 *   inverseJoinColumns={
	 *     @ORM\JoinColumn(name="uid", referencedColumnName="uid")
	 *   }
	 * )
	 *
	 * @Serializer\Type("ArrayCollection<Egb\UserBundle\Entity\Student>")
	 * @Serializer\Expose
	 * @Serializer\MaxDepth(2)
	 */
	private $students;

}

