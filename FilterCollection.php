<?php


namespace App\Filter;


use App\Elastic\Query\Composer;
use App\Elastic\Query\Mapping;
use App\Elastic\Query\MatchBuilder;
use App\Filter\FilterTypes\AbstractFilter;

class FilterCollection
{
    private ?array $mapping = [];
    private array $filters = [];
    private int $iteration = 0;

    public function getAvailableFilters(): array
    {
        return [
            ['field' => 'priceRRP',
                'name' => 'Цена'],
            ['field' => 'material',
                'name' => 'Материал'],
            ['field' => 'brand',
                'name' => 'Бренд'],
            ['field' => 'country',
                'name' => 'Страна производитель'],
        ];

    }


    public function __construct()
    {
        $mapping = new Mapping();
        $mapping = $mapping->getMapping();
        $types = array_column($mapping, 'type');
        $mapping = array_combine(array_keys($mapping), $types);
        $declaredFilters = [];
        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, 'App\Filter\FilterTypes\AbstractFilter')) {
                $declaredFilters[] = $class;
            }
        }
        foreach ($mapping as &$map) {
            foreach ($declaredFilters as $declaredFilter) {
                if (stripos($declaredFilter, $map)) {
                    $map = $declaredFilter;
                }
            }
        }
        unset($map);
        $this->mapping = $mapping;
    }

    public function addFilter($field, $value, $condition = '='): AbstractFilter
    {
        $filter = new $this->mapping[$field];
        $filter->setField($field);
        $filter->setValue($value);
        $filter->setCondition($condition);
        $this->filters[] = $filter;
        return $filter;
    }

    public function nextFilter(): ?AbstractFilter
    {
        $result = null;
        if ($this->filters) {
            if ($this->iteration <= count($this->filters) - 1) {
                $result = $this->filters[$this->iteration];
            } else {
                $result = null;
            }
            $this->iteration++;
        }
        return $result;
    }

    public function refresh(): bool
    {
        $this->iteration = 0;
        return true;
    }

    public function getDefaultFiltersBySection(array $availableFilters, MatchBuilder $matchBuilder): array
    {
        $composer = new Composer();
        $composer->setRequest($matchBuilder);
        foreach ($availableFilters as $filter) {
            $filterObj = new $this->mapping[$filter['field']]();
            $filterObj->setField($filter['field']);
            $filterObj->getAvailable($composer);
            $filterObj->setName($filter['name']);
            $filters[] = $filterObj;
        }
        $composer->size(0);
        $result = $composer->exec();
        foreach ($filters as $filter) {
            if ($result[$filter->getField()]) {
                $result[$filter->getField()] = [
                    'values' => $result[$filter->getField()],
                    'type' => $filter->getType(),
                    'field' => $filter->getField(),
                    'name' => $filter->getName()
                ];
            }
        }

        return array_values($result);
    }
}
