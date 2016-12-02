<?php

namespace Egb\UserBundle\Entity;


use FOS\UserBundle\Model\User as BaseUser;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Entity(repositoryClass="Egb\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 *
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "teacher" = "Teacher", "student" = "Student", "parent" = "Paren"})
 *
 * @Serializer\ExclusionPolicy("all")
 */
class User extends BaseUser {

	/**
	 * Provide discrimiator value.
	 * We cannot use Doctrine annotations, since it will see as a duplicate declaration.
	 */
	protected $userType = 'user';

	/**
	 * @ORM\Id
	 * @ORM\Column(name="uid", type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 *
	 * @Serializer\Expose
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=64, nullable=true)
	 *
	 * @Serializer\Groups({"Default"})
	 * @Serializer\Expose
	 */
	protected $firstname;

	/**
	 * @ORM\Column(type="string", length=64, nullable=true)
	 *
	 * @Serializer\Groups({"Default"})
	 * @Serializer\Expose
	 */
	protected $lastname;

	/**
	 * @ORM\Column(type="datetime", nullable=false)
	 *
	 * @Serializer\Groups({"Default"})
	 * @Serializer\Expose
	 */
	public $created;

	/**
	 * @ORM\Column(type="datetime", nullable=false)
	 *
	 * @Serializer\Groups({"Default"})
	 * @Serializer\Expose
	 */
	public $modified;

	/**
	 * Get the formatted name to display (NAME Firstname or username)
	 *
	 * @param $separator : the separator between name and firstname (default: ' ')
	 * @return String
	 *
	 * @Serializer\VirtualProperty
	 * @Serializer\Groups({"Default", "Me"})
	 */
	public function getUsedName($separator = ' ') {
		if ($this->lastname != null && $this->firstname != null) {
			$ucfirst = function($string) {
				return extension_loaded('mbstring') ? mb_strtoupper(mb_substr($string, 0, 1)).mb_substr($string, 1) : ucfirst($string);
			};
			return $ucfirst($this->firstname).$separator.$ucfirst($this->lastname);
		} else {
			return $this->username;
		}
	}

	public function __get($property) {
		if (property_exists(__CLASS__, $property)) return $this->{$property};
		$method = 'get'.ucfirst($property);
		if (method_exists($this, $method)) return $this->{$method}();
	}

	public function __set($property, $value) {
		if (property_exists(__CLASS__, $property)) $this->{$property} = $value;
	}

	public function __call($method, $args = array()) {
		$m = array();
		if (preg_match('@^(get|set)([A-Z][A-Za-z0-9_]+)$@', $method, $m)) {
			$action = $m[1];
			$property = strtolower($m[2]);
			if (property_exists(__CLASS__, $property)) {
				if ($action == 'set') $this->{$property} = $args[0]; else return $this->{$property};
			}
		}
		return $this->__get($method);
	}

	public function __construct() {
		parent::__construct();
		$this->created = new \DateTime("now");
	}

}

