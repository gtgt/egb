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
 * @ORM\Table(name="teacher")
 * @ORM\HasLifecycleCallbacks()
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
	protected $class;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 *
	 * @ORM\OneToMany(targetEntity="Egb\SubjectBundle\Entity\Subject", mappedBy="teacher")
	 *
	 * @Serializer\Type("ArrayCollection<Egb\SubjectBundle\Entity\Subject>")
	 * @Serializer\Groups({"Default", "Detail"})
	 * @Serializer\Expose
	 * @Serializer\MaxDepth(2)
	 */
	protected $subjects;

	/**
	 * @var ArrayCollection
	 *
	 * @ORM\OneToMany(targetEntity="TeacherConsultingHour", mappedBy="teacher")
	 *
	 * @Serializer\Type("ArrayCollection<Egb\UserBundle\Entity\TeacherConsultingHour>")
	 * @Serializer\Groups({"Default", "Detail"})
	 * @Serializer\Expose
	 * @Serializer\MaxDepth(2)
	 */
	protected $consultingHours;

	public function addConsultingHour(TeacherConsultingHour $tch) {
		if (!$this->consultingHours->contains($tch)) {
			$this->consultingHours->add($tch);
		}
	}
	public function removeConsultingHour(TeacherConsultingHour $tch) {
		if (!$this->consultingHours->contains($tch)) {
			$this->consultingHours->removeElement($tch);
		}
	}

	public function __construct() {
		$this->consultingHours = new ArrayCollection();
		parent::__construct();
	}
}

/**
 * Class TeacherConsultingHour
 * @ORM\Entity
 * @ORM\Table(name="teacher_consulting_hour")
 *
 * @Serializer\ExclusionPolicy("all")
 *
 */
class TeacherConsultingHour {
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Teacher", inversedBy="consultingHours")
	 * @ORM\JoinColumn(name="uid", referencedColumnName="uid")
	 * @Serializer\Expose
	 */
	public $teacher;
	/**
	 * Weekday as described in date() function
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @Assert\Range(min="0", max="6")
	 * @Serializer\Expose
	 */
	public $weekday;

	/**
	 * @ORM\Id
	 * @ORM\Column(type="string", nullable=false)
	 * @Assert\Expression(expression="[0-2][0-9]:[0-2][0-9]")
	 * @Serializer\Expose
	 */
	public $when;

	/**
	 * Length in minutes
	 * @ORM\Column(type="smallint")
	 * @Serializer\Type("integer")
	 * @Assert\Range(min="60", max="240")
	 * @Serializer\Expose
	 */
	public $length = 60;
}
