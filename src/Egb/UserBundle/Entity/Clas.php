<?php

namespace Egb\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clas
 *
 * @ORM\Table(name="class")
 * @ORM\Entity
 */
class Clas
{
    /**
     * @var integer
     *
     * @ORM\Column(name="clid", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToOne(targetEntity="Student", mappedBy="teacher")
     */
    private $teacher;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Student", mappedBy="class")
     */
    private $students;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
    }

}

