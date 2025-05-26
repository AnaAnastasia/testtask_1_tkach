<?php

namespace App\Controllers;

use App\Repositories\RuleRepository;
use App\Services\DbConnection;
use App\Services\HotelDataProvider;
use App\Services\RuleEvaluator;
use PDO;

class IndexController
{
    private PDO $db;

    public function __construct(DbConnection $dbConnection)
    {
        $this->db = $dbConnection->getPdo();
    }

    public function handle(): void
    {
        $hotelId = $_GET['hotel_id'] ?? 1;

        //список отелей
        $stmt = $this->db->query("
        SELECT hotels.id, hotels.name, cities.name AS city_name, countries.name AS country_name, hotels.stars
        FROM hotels
        JOIN cities ON cities.id = hotels.city_id
        JOIN countries ON countries.id = cities.country_id
        ORDER BY hotels.id
    ");
        $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $rules = (new RuleRepository($this->db))->getAllActiveRules();
        $hotelDataProvider = new HotelDataProvider($this->db);

        $results = [];
        $error = '';
        $hotelData = [];

        //проверка отеля
        $hotelDataTest = $hotelDataProvider->getHotelFullData($hotelId, $rules[0]->agency_id ?? 0);
        if (empty($hotelDataTest)) {
            $error = "Отель с ID {$hotelId} не найден или не привязан к агентствам.";
        } else {
            foreach ($rules as $rule) {
                $hotelData = $hotelDataProvider->getHotelFullData($hotelId, $rule->agency_id);
                $evaluator = new RuleEvaluator($hotelData);

                if ($evaluator->evaluate($rule)) {
                    $results[] = "Агентство {$rule->agency_name}: {$rule->message}";
                }
            }
        }

        require __DIR__ . '/../../views/index.php';
    }
}