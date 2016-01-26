<?php

namespace Tests\Weew\Validator\DoctrineConstraints;

use PHPUnit_Framework_TestCase;
use Tests\Weew\Validator\DoctrineConstraints\Stubs\FakeRepository;
use Weew\Validator\DoctrineConstraints\UniqueConstraint;
use Weew\Validator\ValidationData;

class UniqueConstraintTest extends PHPUnit_Framework_TestCase {
    public function test_check() {
        $c = new UniqueConstraint('foo', new FakeRepository());
        $this->assertTrue($c->check('yolo'));
        $this->assertFalse($c->check('found'));
        $this->assertFalse($c->check([]));
    }

    public function test_check_with_multiple_results_with_duplicate() {
        $c = new UniqueConstraint('foo', new FakeRepository());
        $this->assertTrue(
            $c->check('multiple', new ValidationData(['id' => 1]))
        );
        $this->assertFalse(
            $c->check('multiple', new ValidationData(['id' => 2]))
        );

        $c = new UniqueConstraint('foo', new FakeRepository());
        $c->allowIfEqual('id', 'id');
        $this->assertTrue(
            $c->check('multiple', new ValidationData(['id' => 1]))
        );
        $this->assertFalse(
            $c->check('multiple', new ValidationData(['id' => 2]))
        );
    }

    public function test_get_options() {
        $c = new UniqueConstraint('foo', new FakeRepository());
        $this->assertEquals(['column' => 'foo'], $c->getOptions());
    }

    public function test_get_message() {
        $c = new UniqueConstraint('foo', new FakeRepository(), 'foo');
        $this->assertEquals('foo', $c->getMessage());

        $c = new UniqueConstraint('foo', new FakeRepository());
        $this->assertNotNull($c->getMessage());
    }
}
