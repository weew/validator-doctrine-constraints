<?php

namespace Tests\Weew\Validator\DoctrineConstraints\Stubs;

use Doctrine\Common\Persistence\ObjectRepository;

class FakeRepository implements ObjectRepository {
    public function find($id) {}
    public function findAll() {}
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {}
    public function findOneBy(array $criteria) {
        if (array_get($criteria, 'foo') === 'found') {
            return new FakeEntity();
        }

        return null;
    }
    public function getClassName() {}
}
