<?php

namespace App\Filter\FilterTypes;

use App\Elastic\Query\Composer;
use Elasticsearch\ClientBuilder;

class TextFilter extends AbstractFilter
{
    protected string $value;

    public function getValue()
    {
        return $this->value;
    }

    public function getAvailable(Composer $composer): Composer
    {
        $composer->aggregate($this->field);
        return $composer;
    }

    public function getType(): string
    {
        return 'checkbox';
    }
}