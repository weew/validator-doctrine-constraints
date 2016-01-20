<?php

namespace Weew\Validator\DoctrineConstraints;

use Doctrine\Common\Persistence\ObjectRepository;
use Weew\Validator\IConstraint;

/**
 * Ensure that a column value is unique.
 */
class UniqueConstraint implements IConstraint {
    /**
     * @var
     */
    protected $column;

    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * UniqueConstraint constructor.
     *
     * @param $column
     * @param ObjectRepository $repository
     */
    public function __construct($column, ObjectRepository $repository) {
        $this->column = $column;
        $this->repository = $repository;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function check($value) {
        if (is_scalar($value)) {
            $duplicate = $this->repository
                ->findOneBy([$this->column => $value]);

            return $duplicate === null;
        }

        return false;
    }

    /**
     * @return array
     */
    public function toArray() {
        return [
            'column' => $this->column,
        ];
    }
}
