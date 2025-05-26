<?php

namespace App\Entities;

class RuleCondition
{
    public string $field;
    public string $operator;
    public string $value;

    public function __construct(array $data)
    {
        $this->field = $data['field'];
        $this->operator = $data['operator'];
        $this->value = $data['value'];
    }
}