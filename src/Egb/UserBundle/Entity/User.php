<?php

namespace Egb\UserBundle\Entity;


use FOS\UserBundle\Model\User as BaseUser;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Egb\UserBundle\Repository\UserRepository")
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
	public function getUserType() {
		return 'user';
	}

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
	 * @Serializer\Groups({"Detail", "Me"})
	 * @Serializer\Expose
	 */
	protected $firstname;

	/**
	 * @ORM\Column(type="string", length=64, nullable=true)
	 *
	 * @Serializer\Groups({"Detail", "Me"})
	 * @Serializer\Expose
	 */
	protected $lastname;

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
		if ($this->getName() != null && $this->getFirstName() != null) {
			return ucfirst(strtolower($this->getFirstName())).$separator.strtoupper($this->getName());
		} else {
			return $this->getUsername();
		}
	}

	public function getType() {

	}

	public function __get($property) {
		if (property_exists(__CLASS__, $property)) return $this->{$property};
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
	}

}

