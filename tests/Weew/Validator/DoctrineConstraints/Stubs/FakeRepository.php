<?php

namespace Tests\Weew\Validator\DoctrineConstraints\Stubs;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class FakeRepository extends EntityRepository {
    public function __construct() {

    }

    public function find($id) {}
    public function findAll() {}
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {}
    public function findOneBy(array $criteria) {
        if (array_get($criteria, 'foo') === 'found') {
            return new FakeEntity();
        } else if (array_get($criteria, 'foo') === 'multiple') {
            if (array_get($criteria, 'id') === 1) {
                return new FakeEntity();
            } else if ( ! array_has($criteria, 'id')) {
                return new FakeEntity();
            }
        }

        return null;
    }
    public function getClassName() {}

    public function createQueryBuilder($alias, $indexBy = null) {
        $q = new QueryBuilder(new FakeEntityManager());
        $q->select($alias)
            ->from('foo', $alias, $indexBy);

        return $q;
    }
}
