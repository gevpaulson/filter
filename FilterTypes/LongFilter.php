<?php


namespace App\Filter\FilterTypes;


use App\Elastic\Query\Composer;

class LongFilter extends AbstractFilter
{
    protected int $value;

    public function setValue($value): bool
    {
        if (gettype($value) != 'integer') {
            $this->value = (int) $value;
        }
        $this->value = $value;
        return true;
    }

    public function getAvailable(Composer $composer): Composer
    {
        $composer
            ->min($this->field)
            ->max($this->field);
        return $composer;
    }

    public function getType(): string
    {
        return 'range';
    }
}