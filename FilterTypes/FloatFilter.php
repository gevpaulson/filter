<?php


namespace App\Filter\FilterTypes;


use App\Elastic\Query\Composer;

class FloatFilter extends AbstractFilter
{
    protected float $value;

    public function setValue($value): bool
    {
        if (gettype($value) != 'float') {
            $value = (float) str_replace(',', '.', $value);
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
        return 'range';
    }
}