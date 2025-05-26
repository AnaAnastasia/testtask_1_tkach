<?php

namespace App\Repositories;

use App\Entities\Rule;
use App\Entities\RuleCondition;
use PDO;

class RuleRepository
{
    public function __construct(private PDO $db) {}

    public function getAllActiveRules(): array
    {
        $stmt = $this->db->query("
            SELECT rules.*, agencies.name AS agency_name
            FROM rules
            JOIN agencies ON agencies.id = rules.agency_id
            WHERE rules.is_active = 1
        ");

        $rulesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rules = [];

        foreach ($rulesData as $data) {
            $rule = new Rule($data);
            $rule->conditions = $this->getConditionsForRule($rule->id);
            $rules[] = $rule;
        }

        return $rules;
    }

    private function getConditionsForRule($ruleId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM rule_conditions WHERE rule_id = :id");
        $stmt->execute(['id' => $ruleId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $conditions = [];
        foreach ($rows as $row) {
            $condition = new RuleCondition($row);
            $conditions[] = $condition;
        }

        return $conditions;
    }

}