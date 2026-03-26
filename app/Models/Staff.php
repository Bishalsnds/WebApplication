<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Staff
{
    private PDO $db;
    private ?bool $hasRichProfileColumns = null;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllStaff(): array
    {
        $profileFields = $this->getProfileSelectFields();
        $sql = "SELECT StaffID, Name, {$profileFields} FROM staff ORDER BY Name ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getById(int $staffId): ?array
    {
        $profileFields = $this->getProfileSelectFields();
        $stmt = $this->db->prepare("SELECT StaffID, Name, {$profileFields} FROM staff WHERE StaffID = ? LIMIT 1");
        $stmt->execute([$staffId]);
        $staff = $stmt->fetch();

        return $staff ?: null;
    }

    public function getModulesLedByStaff(int $staffId): array
    {
        $sql = "
            SELECT
                m.ModuleID,
                m.ModuleName,
                m.Description,
                COUNT(DISTINCT pm.ProgrammeID) AS ProgrammeCount,
                GROUP_CONCAT(DISTINCT p.ProgrammeName ORDER BY p.ProgrammeName SEPARATOR ', ') AS ProgrammeNames
            FROM modules m
            LEFT JOIN programmemodules pm ON pm.ModuleID = m.ModuleID
            LEFT JOIN programmes p ON p.ProgrammeID = pm.ProgrammeID
            WHERE m.ModuleLeaderID = ?
            GROUP BY m.ModuleID, m.ModuleName, m.Description
            ORDER BY m.ModuleName ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$staffId]);

        return $stmt->fetchAll();
    }

    public function getProgrammesByStaff(int $staffId): array
    {
        $sql = "
            SELECT DISTINCT
                p.ProgrammeID,
                p.ProgrammeName,
                l.LevelName
            FROM modules m
            INNER JOIN programmemodules pm ON pm.ModuleID = m.ModuleID
            INNER JOIN programmes p ON p.ProgrammeID = pm.ProgrammeID
            LEFT JOIN levels l ON l.LevelID = p.LevelID
            WHERE m.ModuleLeaderID = ?
            ORDER BY p.ProgrammeName ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$staffId]);

        return $stmt->fetchAll();
    }

    private function getProfileSelectFields(): string
    {
        if ($this->supportsRichProfiles()) {
            return 'JobTitle, Department, Bio, PhotoUrl';
        }

        return 'NULL AS JobTitle, NULL AS Department, NULL AS Bio, NULL AS PhotoUrl';
    }

    private function supportsRichProfiles(): bool
    {
        if ($this->hasRichProfileColumns !== null) {
            return $this->hasRichProfileColumns;
        }

        $columns = $this->db->query('SHOW COLUMNS FROM staff')->fetchAll(PDO::FETCH_COLUMN, 0);
        $lookup = array_flip($columns);
        $this->hasRichProfileColumns = isset($lookup['JobTitle'], $lookup['Department'], $lookup['Bio'], $lookup['PhotoUrl']);

        return $this->hasRichProfileColumns;
    }
}
