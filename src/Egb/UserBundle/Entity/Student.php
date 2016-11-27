<?php

namespace Egb\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity
 */
class Student extends User
{

    /**
     * @ORM\ManyToOne(targetEntity="Paren", inversedBy="students")
     * @ORM\JoinColumn(name="parent_uid", referencedColumnName="uid")
     */
    private $parent;

    /**
     * @var Clas
     *
     * @ORM\OneToOne(targetEntity="Clas", inversedBy="students")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clid", referencedColumnName="clid", unique=true)
     * })
     */
    private $class;


}

