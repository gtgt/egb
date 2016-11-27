<?php

namespace Egb\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 *
 * @ORM\Table(name="teacher")
 * @ORM\Entity
 */
class Teacher extends User
{

    /**
     * @var Clas
     *
     * @ORM\OneToOne(targetEntity="Clas", inversedBy="teacher")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clid", referencedColumnName="clid", unique=true)
     * })
     */
    private $class;

    /**
		 * @var \Doctrine\Common\Collections\Collection
		 *
     * @ORM\OneToMany(targetEntity="Subject", mappedBy="teacher")
     */
    private $subjects;

    public function __construct() {
        $this->subjects = new \Doctrine\Common\Collections\ArrayCollection();
    }
}

