<?php

namespace App\Controllers;

use App\Services\DbConnection;
use PDO;

class ConditionController
{
    private PDO $db;

    public function __construct(DbConnection $connection)
    {
        $this->db = $connection->getPdo();
    }

    public function handle(): void
    {
        $ruleId = $_GET['rule_id'] ?? null;

        //проверка, существует ли правило
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM rules WHERE id = ?");
        $stmt->execute([$ruleId]);
        if ($stmt->fetchColumn() == 0) {
            die("Ошибка: правило #{$ruleId} не найдено в базе данных.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $field = $_POST['field'] ?? '';
            $operator = $_POST['operator'] ?? '=';
            $value = $_POST['value'] ?? '';

            $stmt = $this->db->prepare("INSERT INTO rule_conditions (rule_id, field, operator, value) VALUES (?, ?, ?, ?)");
            $stmt->execute([$ruleId, $field, $operator, $value]);
            header("Location: rules.php?action=edit&id={$ruleId}");
            exit;
        }

        $availableFields = [
            'country_id' => 'Страна отеля',
            'city_id' => 'Город отеля',
            'stars' => 'Звёзды отеля',
            'discount_percent' => 'Скидка по договору',
            'comission_percent' => 'Комиссия по договору',
            'is_default' => 'Договор по умолчанию',
            'company_id' => 'Компания по договору',
            'is_black' => 'Чёрный список',
            'is_white' => 'Белый список',
            'is_recomend' => 'Рекомендованный отель',
        ];

        $availableOperators = [
            '=' => 'Равно',
            '!=' => 'Не равно',
            '>' => 'Больше',
            '<' => 'Меньше'
        ];

        $binaryFields = ['is_black', 'is_white', 'is_recomend', 'is_default'];
        $selectFields = ['country_id', 'city_id'];

        $selectOptions = [
            'country_id' => $this->db->query("SELECT id, name FROM countries ORDER BY name")->fetchAll(PDO::FETCH_KEY_PAIR),
            'city_id' => $this->db->query("SELECT id, name FROM cities ORDER BY name")->fetchAll(PDO::FETCH_KEY_PAIR),
        ];

        extract([
            'ruleId' => $ruleId,
            'availableFields' => $availableFields,
            'availableOperators' => $availableOperators,
            'binaryFields' => $binaryFields,
            'selectFields' => $selectFields,
            'selectOptions' => $selectOptions,
        ]);

        require __DIR__ . '/../../views/condition_form.php';
    }
}