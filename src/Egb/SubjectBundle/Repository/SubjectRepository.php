<?php
namespace Egb\SubjectBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;

/**
 * {@inheritDoc}
 */
class SubjectRepository extends EntityRepository {
	/**
	 * @var EntityManager
	 */
	private $em;

	public function __construct(EntityManager $em, Mapping\ClassMetadata $class) {
		parent::__construct($em, $class);
		$this->em = $em;
	}
}