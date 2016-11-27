<?php

namespace Egb\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subject
 *
 * @ORM\Table(name="subject")
 * @ORM\Entity
 */
class Subject
{
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
     */
    private $students;

    /**
		 * @var Teacher
		 *
     * @ORM\ManyToOne(targetEntity="Teacher", inversedBy="subjects")
     * @ORM\JoinColumn(name="uid", referencedColumnName="uid")
     */
    private $teacher;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
    }

}

