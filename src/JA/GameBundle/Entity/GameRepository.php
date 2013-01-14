<?php

namespace JA\GameBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GameRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GameRepository extends EntityRepository
{
	function findOneWithAllDependencies($id)
	{
		return $this->createQueryBuilder('g')
					->where(':id = g.id')
					->setParameter('id', $id)
					->innerJoin('g.news', 'n')
					->addSelect('n')
					->getQuery()
					->getSingleResult();
	}
	
	function findAllWithAllDependencies()
	{
		return $this->createQueryBuilder('g')
					->innerJoin('g.news', 'n')
					->addSelect('n')
					->getQuery()
					->getResult();
	}
}
