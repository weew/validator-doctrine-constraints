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
     * @var string
     */
    protected $message;

    /**
     * UniqueConstraint constructor.
     *
     * @param $column
     * @param ObjectRepository $repository
     * @param null $message
     */
    public function __construct(
        $column,
        ObjectRepository $repository,
        $message = null
    ) {
        $this->column = $column;
        $this->repository = $repository;
        $this->message = $message;
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
     * @return string
     */
    public function getMessage() {
        if ($this->message !== null) {
            return $this->message;
        }

        return 'This value is not available.';
    }

    /**
     * @return array
     */
    public function getOptions() {
        return [
            'column' => $this->column,
        ];
    }
}
