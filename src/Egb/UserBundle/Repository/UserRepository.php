<?php
namespace Egb\UserBundle\Repository;

use Egb\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;

class UserRepository extends EntityRepository {
	/**
	 * Update user
	 *
	 * @param $user Entity\Paren|Entity\Student|Entity\Teacher|Entity\User
	 * @return Entity\Paren|Entity\Student|Entity\Teacher|Entity\User
	 */
	public function update($user) {
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