<?php

namespace App\Entities;

class Rule
{
    public int $id;
    public int $agency_id;
    public string $name;
    public string $message;
    public bool $is_active;

    public array $conditions = [];
    public string $agency_name = '';

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->agency_id = $data['agency_id'];
        $this->name = $data['name'];
        $this->message = $data['message'];
        $this->is_active = $data['is_active'];
        $this->agency_name = $data['agency_name'];
    }
}