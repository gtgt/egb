<?php
namespace Egb\UserBundle\Repository;

use Egb\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;

class UserRepository extends EntityRepository {

	/**
	 * @return string
	 */
	public function getClassName() {
		return parent::getClassName();
	}

	/**
	 * @return string
	 */
	public function getEntityName() {
		return parent::getEntityName();
	}

	/**
	 * @return EntityManager
	 */
	public function getEntityManager() {
		return parent::getEntityManager();
	}

	/**
	 * @return Mapping\ClassMetadata
	 */
	public function getClassMetadata() {
		return parent::getClassMetadata();
	}

	/**
	 * Update user
	 *
	 * @param $user Entity\Paren|Entity\Student|Entity\Teacher|Entity\User
	 * @return Entity\Paren|Entity\Student|Entity\Teacher|Entity\User
	 */
	public function update($user) {
		//make salt if user is new
		if (empty($user->getId())) $user->setSalt(uniqid(uniqid(true), true));
		//update timestamps
		if (!(int)$user->created->format("u")) $user->created = new \DateTime("now");
		$user->modified = new \DateTime("now");
		//store
		$em = $this->getEntityManager();
		$em->persist($user);
		$em->flush($user);
		return $user;
	}


}
