<?php

namespace App\Services;

use PDO;

class HotelDataProvider
{
    public function __construct(private PDO $db) {}

    public function getHotelFullData(int $hotelId, int $agencyId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                hotels.id,
                hotels.name,
                hotels.city_id,
                hotels.stars,
                cities.country_id,
                hotel_agreements.discount_percent,
                hotel_agreements.comission_percent,
                hotel_agreements.is_default,
                hotel_agreements.company_id,
                agency_hotel_options.is_black,
                agency_hotel_options.is_white,
                agency_hotel_options.is_recomend
            FROM hotels
                JOIN cities
                    ON hotels.city_id = cities.id
                LEFT JOIN hotel_agreements
                    ON hotel_agreements.hotel_id = hotels.id
                    AND hotel_agreements.is_default = 1
                LEFT JOIN agency_hotel_options
                    ON agency_hotel_options.hotel_id = hotels.id
                    AND agency_hotel_options.agency_id = :agency_id
            WHERE 
                hotels.id = :id
                LIMIT 1;
        ");
        $stmt->execute(['id' => $hotelId, 'agency_id' => $agencyId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

}