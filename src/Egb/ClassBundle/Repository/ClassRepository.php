<?php
namespace Egb\ClassBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;

/**
 * {@inheritDoc}
 */
class ClassRepository extends EntityRepository {
	/**
	 * @var EntityManager
	 */
	private $em;

	public function __construct(EntityManager $em, Mapping\ClassMetadata $class) {
		parent::__construct($em, $class);
		$this->em = $em;
	}
}