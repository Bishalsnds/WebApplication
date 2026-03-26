<?php
// app/Models/Module.php

namespace App\Models;

use App\Core\Database;
use PDO;

class Module
{
    private PDO $db;
    private ?bool $hasImageAltColumn = null;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllWithLeader(): array
    {
        $imageAlt = $this->supportsImageAlt() ? 'm.ImageAlt' : 'NULL AS ImageAlt';

        $sql = "
            SELECT m.ModuleID, m.ModuleName, m.Description, m.Image, {$imageAlt}, m.ModuleLeaderID, s.Name AS ModuleLeaderName
            FROM modules m
            LEFT JOIN staff s ON s.StaffID = m.ModuleLeaderID
            ORDER BY m.ModuleName ASC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function getById(int $moduleId): ?array
    {
        $imageAlt = $this->supportsImageAlt() ? 'ImageAlt' : 'NULL AS ImageAlt';
        $stmt = $this->db->prepare(
            "SELECT ModuleID, ModuleName, Description, Image, {$imageAlt}, ModuleLeaderID FROM modules WHERE ModuleID = ? LIMIT 1"
        );
        $stmt->execute([$moduleId]);
        $module = $stmt->fetch();

        return $module ?: null;
    }

    public function create(array $data): int
    {
        $newId = $this->nextModuleId();

        if ($this->supportsImageAlt()) {
            $stmt = $this->db->prepare(
                'INSERT INTO modules (ModuleID, ModuleName, ModuleLeaderID, Description, Image, ImageAlt) VALUES (?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $newId,
                $data['ModuleName'],
                $data['ModuleLeaderID'],
                $data['Description'],
                $data['Image'],
                $data['ImageAlt'] ?? null,
            ]);
        } else {
            $stmt = $this->db->prepare(
                'INSERT INTO modules (ModuleID, ModuleName, ModuleLeaderID, Description, Image) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $newId,
                $data['ModuleName'],
                $data['ModuleLeaderID'],
                $data['Description'],
                $data['Image'],
            ]);
        }

        return $newId;
    }

    public function update(int $moduleId, array $data): bool
    {
        if ($this->supportsImageAlt()) {
            $stmt = $this->db->prepare(
                'UPDATE modules SET ModuleName = ?, ModuleLeaderID = ?, Description = ?, Image = ?, ImageAlt = ? WHERE ModuleID = ?'
            );

            return $stmt->execute([
                $data['ModuleName'],
                $data['ModuleLeaderID'],
                $data['Description'],
                $data['Image'],
                $data['ImageAlt'] ?? null,
                $moduleId,
            ]);
        }

        $stmt = $this->db->prepare(
            'UPDATE modules SET ModuleName = ?, ModuleLeaderID = ?, Description = ?, Image = ? WHERE ModuleID = ?'
        );

        return $stmt->execute([
            $data['ModuleName'],
            $data['ModuleLeaderID'],
            $data['Description'],
            $data['Image'],
            $moduleId,
        ]);
    }

    public function delete(int $moduleId): bool
    {
        $deleteLinks = $this->db->prepare('DELETE FROM programmemodules WHERE ModuleID = ?');
        $deleteLinks->execute([$moduleId]);

        $stmt = $this->db->prepare('DELETE FROM modules WHERE ModuleID = ?');
        return $stmt->execute([$moduleId]);
    }

    public function assignToProgramme(int $moduleId, int $programmeId, int $year): bool
    {
        $check = $this->db->prepare(
            'SELECT ProgrammeModuleID FROM programmemodules WHERE ModuleID = ? AND ProgrammeID = ? LIMIT 1'
        );
        $check->execute([$moduleId, $programmeId]);
        $existing = $check->fetch();

        if ($existing) {
            $update = $this->db->prepare('UPDATE programmemodules SET Year = ? WHERE ProgrammeModuleID = ?');
            return $update->execute([$year, $existing['ProgrammeModuleID']]);
        }

        $insert = $this->db->prepare('INSERT INTO programmemodules (ProgrammeID, ModuleID, Year) VALUES (?, ?, ?)');
        return $insert->execute([$programmeId, $moduleId, $year]);
    }

    public function removeFromProgramme(int $moduleId, int $programmeId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM programmemodules WHERE ModuleID = ? AND ProgrammeID = ?');
        return $stmt->execute([$moduleId, $programmeId]);
    }

    private function nextModuleId(): int
    {
        $row = $this->db->query('SELECT COALESCE(MAX(ModuleID), 0) + 1 AS nextId FROM modules')->fetch();
        return (int) $row['nextId'];
    }

    private function supportsImageAlt(): bool
    {
        if ($this->hasImageAltColumn !== null) {
            return $this->hasImageAltColumn;
        }

        $stmt = $this->db->query("SHOW COLUMNS FROM modules LIKE 'ImageAlt'");
        $this->hasImageAltColumn = (bool) $stmt->fetch();

        return $this->hasImageAltColumn;
    }
}
