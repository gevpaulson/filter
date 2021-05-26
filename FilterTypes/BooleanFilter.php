<?php


namespace App\Filter\FilterTypes;


use App\Elastic\Query\Composer;

class BooleanFilter extends AbstractFilter
{
    protected bool $value;

    public function setValue($value): bool
    {
        if (gettype($value) != 'bool') {
            $value = (bool) $value;
        }
        return $value;
    }

    public function getAvailable(Composer $composer): Composer
    {
        dump('not realized');
        return $composer;
    }

    public function getType(): string
    {
        return 'checkbox';
    }
}