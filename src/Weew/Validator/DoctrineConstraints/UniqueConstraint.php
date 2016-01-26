<?php

namespace Weew\Validator\DoctrineConstraints;

use Doctrine\Common\Persistence\ObjectRepository;
use Weew\Validator\IConstraint;
use Weew\Validator\IValidationData;
use Weew\Validator\PropertyReader;

/**
 * Ensure that a column value is unique.
 */
class UniqueConstraint implements IConstraint {
    /**
     * @var string
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
     * @var string
     */
    protected $controlColumn;

    /**
     * @var string
     */
    protected $controlProperty;

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

        $this->allowIfEqual('id', 'id');
    }

    /**
     * @param $value
     * @param IValidationData $data
     *
     * @return bool
     */
    public function check($value, IValidationData $data = null) {
        if (is_scalar($value)) {
            $alreadyExists = false;

            if ($data instanceof IValidationData) {
                $controlValue = $data->get($this->controlProperty);

                if ($controlValue !== null) {
                    $alreadyExists = $this->repository
                        ->findOneBy([
                            $this->column => $value,
                            $this->controlColumn => $controlValue
                        ]) !== null;
                }
            }

            $duplicate = $this->repository
                ->findOneBy([$this->column => $value]);

            return $alreadyExists === true || $duplicate === null;
        }

        return false;
    }

    public function allowIfEqual($column, $property) {
        $this->controlColumn = $column;
        $this->controlProperty = $property;
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
