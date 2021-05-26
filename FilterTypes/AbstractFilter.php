<?php
namespace App\Filter\FilterTypes;

use App\Elastic\Query\Composer;

abstract class AbstractFilter {
    protected string $field;
    protected string $condition;
    protected string $name;

    public function setField(string $field): bool
    {
        $this->field = $field;
        return true;
    }
    public function getField(): string
    {
        return $this->field;
    }

    public function setValue($value): bool
    {
        $this->value = $value;
        return true;
    }

    abstract public function getType(): string;

    public function setCondition($condition = '='): bool
    {
        if ($condition == '>=') {
            $condition = 'gte';
        } elseif ($condition == '<=') {
            $condition = 'lte';
        } elseif ($condition == '>') {
            $condition = 'ge';
        } elseif ($condition == '<') {
            $condition = 'le';
        }
        $this->condition = $condition;
        return true;
    }
    public function getCondition(): string
    {
        return $this->condition;
    }

    public function getValue() {
        return $this->value;
    }

    public function setName(string $name): bool
    {
        $this->name = $name;
        return true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    abstract public function getAvailable(Composer $composer): Composer;
}