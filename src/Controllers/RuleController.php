<?php

namespace App\Controllers;

use App\Services\DbConnection;
use PDO;

class RuleController
{
    private $db;

    public function __construct(DbConnection $connection)
    {
        $this->db = $connection->getPdo();
    }

    public function handle(): void
    {
        $action = $_GET['action'] ?? 'list';

        //удаление правила
        if ($action === 'delete' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $this->db->prepare("DELETE FROM rules WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: rules.php");
            exit;
        }

        //обработка формы добавления/редактирования
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
            return;
        }

        //форма добавления нового правила
        if ($action === 'add') {
            $agencies = $this->getAgencies();
            $rule = [
                'id'         => null,
                'agency_id'  => '',
                'name'       => '',
                'message'    => '',
                'is_active'  => 1
            ];
            require __DIR__ . '/../../views/rule_form.php';
            return;
        }

        //форма редактирования существующего правила
        if ($action === 'edit' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $this->db->prepare("SELECT * FROM rules WHERE id = ?");
            $stmt->execute([$id]);
            $rule = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$rule) {
                echo "Правило не найдено.";
                return;
            }

            $agencies = $this->getAgencies();
            require __DIR__ . '/../../views/rule_form.php';
            return;
        }

        //удаление условия правила
        if ($action === 'delete_condition' && isset($_GET['cid'], $_GET['rule_id'])) {
            $cid = $_GET['cid'];
            $ruleId = $_GET['rule_id'];
            $stmt = $this->db->prepare("DELETE FROM rule_conditions WHERE id = ?");
            $stmt->execute([$cid]);
            header("Location: rules.php?action=edit&id=" . $ruleId);
            exit;
        }

        //список правил
        $sql = "
            SELECT rules.*, agencies.name AS agency_name
            FROM rules
            JOIN agencies ON agencies.id = rules.agency_id
            ORDER BY rules.id DESC
        ";
        $rules = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../../views/rules_list.php';
    }

    private function handlePost(): void
    {
        $action   = $_POST['action'] ?? '';
        $agencyId = $_POST['agency_id'] ?? null;
        $name     = $_POST['name'] ?? '';
        $message  = $_POST['message'] ?? '';
        $isActive = !isset($_POST['is_active']) ? 0 : 1;

        if ($action === 'add') {
            $stmt = $this->db->prepare("
                INSERT INTO rules (agency_id, name, message, is_active)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$agencyId, $name, $message, $isActive]);
        }

        if ($action === 'edit' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $stmt = $this->db->prepare("
                UPDATE rules
                SET agency_id = ?, name = ?, message = ?, is_active = ?
                WHERE id = ?
            ");
            $stmt->execute([$agencyId, $name, $message, $isActive, $id]);
        }

        header("Location: rules.php");
        exit;
    }

    private function getAgencies(): array
    {
        $stmt = $this->db->query("SELECT * FROM agencies ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}