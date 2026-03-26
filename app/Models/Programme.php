<?php
// app/Models/Programme.php

namespace App\Models;

use App\Core\Database;
use PDO;

class Programme
{
    private PDO $db;
    private ?bool $hasPublishedColumn = null;
    private ?bool $hasStaffProfileColumns = null;
    private ?bool $hasProgrammeImageAltColumn = null;
    private ?bool $hasModuleImageAltColumn = null;
    private ?bool $hasProgrammeFullTextIndex = null;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getPublishedProgrammes(?int $levelId = null, string $search = ''): array
    {
        $programmeImageAlt = $this->supportsProgrammeImageAlt()
            ? 'p.ImageAlt'
            : 'NULL AS ImageAlt';
        $search = trim($search);

        $sql = "
            SELECT
                p.ProgrammeID,
                p.ProgrammeName,
                p.Description,
                p.Image,
                {$programmeImageAlt},
                l.LevelName,
                s.Name AS ProgrammeLeaderName
            FROM programmes p
            LEFT JOIN levels l ON l.LevelID = p.LevelID
            LEFT JOIN staff s ON s.StaffID = p.ProgrammeLeaderID
        ";

        $conditions = [];
        $params = [];

        if ($this->supportsPublishing()) {
            $conditions[] = 'p.IsPublished = 1';
        }

        if ($levelId !== null && $levelId > 0) {
            $conditions[] = 'p.LevelID = ?';
            $params[] = $levelId;
        }

        if ($search !== '') {
            $fullTextQuery = $this->buildBooleanFullTextQuery($search);

            if ($this->supportsProgrammeFullText() && $fullTextQuery !== '') {
                $conditions[] = '(MATCH (p.ProgrammeName, p.Description) AGAINST (? IN BOOLEAN MODE) OR l.LevelName LIKE ?)';
                $params[] = $fullTextQuery;
                $params[] = '%' . $search . '%';
            } else {
                $conditions[] = '(p.ProgrammeName LIKE ? OR p.Description LIKE ? OR l.LevelName LIKE ?)';
                $wild = '%' . $search . '%';
                $params[] = $wild;
                $params[] = $wild;
                $params[] = $wild;
            }
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY p.ProgrammeName ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getAllProgrammesForAdmin(): array
    {
        $selectPublished = $this->supportsPublishing()
            ? 'p.IsPublished'
            : '1 AS IsPublished';

        $sql = "
            SELECT
                p.ProgrammeID,
                p.ProgrammeName,
                p.Description,
                p.Image,
                " . ($this->supportsProgrammeImageAlt() ? 'p.ImageAlt' : 'NULL AS ImageAlt') . ",
                p.LevelID,
                p.ProgrammeLeaderID,
                {$selectPublished},
                l.LevelName,
                s.Name AS ProgrammeLeaderName,
                COUNT(pm.ProgrammeModuleID) AS ModuleCount
            FROM programmes p
            LEFT JOIN levels l ON l.LevelID = p.LevelID
            LEFT JOIN staff s ON s.StaffID = p.ProgrammeLeaderID
            LEFT JOIN programmemodules pm ON pm.ProgrammeID = p.ProgrammeID
            GROUP BY p.ProgrammeID
            ORDER BY p.ProgrammeName ASC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function getById(int $programmeId): ?array
    {
        $selectPublished = $this->supportsPublishing()
            ? 'p.IsPublished'
            : '1 AS IsPublished';
        $programmeLeaderProfile = $this->getStaffProfileSelect('s', 'ProgrammeLeader');

        $sql = "
            SELECT
                p.ProgrammeID,
                p.ProgrammeName,
                p.Description,
                p.Image,
                " . ($this->supportsProgrammeImageAlt() ? 'p.ImageAlt' : 'NULL AS ImageAlt') . ",
                p.LevelID,
                p.ProgrammeLeaderID,
                {$selectPublished},
                l.LevelName,
                s.Name AS ProgrammeLeaderName,
                {$programmeLeaderProfile}
            FROM programmes p
            LEFT JOIN levels l ON l.LevelID = p.LevelID
            LEFT JOIN staff s ON s.StaffID = p.ProgrammeLeaderID
            WHERE p.ProgrammeID = ?
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$programmeId]);
        $programme = $stmt->fetch();

        if (!$programme) {
            return null;
        }

        $programme['modulesByYear'] = $this->getModulesByYear($programmeId);

        return $programme;
    }

    public function getModulesByYear(int $programmeId): array
    {
        $hasStaffProfiles = $this->supportsStaffProfiles();
        $moduleLeaderProfile = $this->getStaffProfileSelect('s', 'ModuleLeader');
        $moduleGroupByStaff = $hasStaffProfiles ? ', s.JobTitle, s.Department, s.Bio, s.PhotoUrl' : '';
        $moduleImageAlt = $this->supportsModuleImageAlt()
            ? 'm.ImageAlt'
            : 'NULL AS ImageAlt';

        $sql = "
            SELECT
                pm.Year,
                m.ModuleID,
                m.ModuleName,
                m.Description,
                m.Image,
                {$moduleImageAlt},
                s.Name AS ModuleLeaderName,
                {$moduleLeaderProfile},
                COUNT(DISTINCT pm_other.ProgrammeID) AS SharedProgrammeCount,
                GROUP_CONCAT(DISTINCT p_other.ProgrammeName ORDER BY p_other.ProgrammeName SEPARATOR ', ') AS SharedProgrammeNames
            FROM programmemodules pm
            INNER JOIN modules m ON m.ModuleID = pm.ModuleID
            LEFT JOIN staff s ON s.StaffID = m.ModuleLeaderID
            LEFT JOIN programmemodules pm_other ON pm_other.ModuleID = m.ModuleID AND pm_other.ProgrammeID <> pm.ProgrammeID
            LEFT JOIN programmes p_other ON p_other.ProgrammeID = pm_other.ProgrammeID
            WHERE pm.ProgrammeID = ?
            GROUP BY pm.ProgrammeModuleID, pm.Year, m.ModuleID, m.ModuleName, m.Description, m.Image, s.Name{$moduleGroupByStaff}
            ORDER BY pm.Year ASC, m.ModuleName ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$programmeId]);
        $rows = $stmt->fetchAll();

        $byYear = [];
        foreach ($rows as $row) {
            $year = (int) ($row['Year'] ?? 0);
            if (!isset($byYear[$year])) {
                $byYear[$year] = [];
            }
            $byYear[$year][] = $row;
        }

        return $byYear;
    }

    public function getLevels(): array
    {
        return $this->db->query('SELECT LevelID, LevelName FROM levels ORDER BY LevelID ASC')->fetchAll();
    }

    public function getStaff(): array
    {
        return $this->db->query('SELECT StaffID, Name FROM staff ORDER BY Name ASC')->fetchAll();
    }

    public function create(array $data): int
    {
        $hasImageAlt = $this->supportsProgrammeImageAlt();

        if ($this->supportsPublishing()) {
            if ($hasImageAlt) {
                $stmt = $this->db->prepare(
                    'INSERT INTO programmes (ProgrammeName, LevelID, ProgrammeLeaderID, Description, Image, ImageAlt, IsPublished) VALUES (?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([
                    $data['ProgrammeName'],
                    $data['LevelID'],
                    $data['ProgrammeLeaderID'],
                    $data['Description'],
                    $data['Image'],
                    $data['ImageAlt'] ?? null,
                    $data['IsPublished'],
                ]);
            } else {
                $stmt = $this->db->prepare(
                    'INSERT INTO programmes (ProgrammeName, LevelID, ProgrammeLeaderID, Description, Image, IsPublished) VALUES (?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([
                    $data['ProgrammeName'],
                    $data['LevelID'],
                    $data['ProgrammeLeaderID'],
                    $data['Description'],
                    $data['Image'],
                    $data['IsPublished'],
                ]);
            }
        } else {
            if ($hasImageAlt) {
                $stmt = $this->db->prepare(
                    'INSERT INTO programmes (ProgrammeName, LevelID, ProgrammeLeaderID, Description, Image, ImageAlt) VALUES (?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([
                    $data['ProgrammeName'],
                    $data['LevelID'],
                    $data['ProgrammeLeaderID'],
                    $data['Description'],
                    $data['Image'],
                    $data['ImageAlt'] ?? null,
                ]);
            } else {
                $stmt = $this->db->prepare(
                    'INSERT INTO programmes (ProgrammeName, LevelID, ProgrammeLeaderID, Description, Image) VALUES (?, ?, ?, ?, ?)'
                );
                $stmt->execute([
                    $data['ProgrammeName'],
                    $data['LevelID'],
                    $data['ProgrammeLeaderID'],
                    $data['Description'],
                    $data['Image'],
                ]);
            }
        }

        return (int) $this->db->lastInsertId();
    }

    public function update(int $programmeId, array $data): bool
    {
        $hasImageAlt = $this->supportsProgrammeImageAlt();

        if ($this->supportsPublishing()) {
            if ($hasImageAlt) {
                $stmt = $this->db->prepare(
                    'UPDATE programmes SET ProgrammeName = ?, LevelID = ?, ProgrammeLeaderID = ?, Description = ?, Image = ?, ImageAlt = ?, IsPublished = ? WHERE ProgrammeID = ?'
                );
                return $stmt->execute([
                    $data['ProgrammeName'],
                    $data['LevelID'],
                    $data['ProgrammeLeaderID'],
                    $data['Description'],
                    $data['Image'],
                    $data['ImageAlt'] ?? null,
                    $data['IsPublished'],
                    $programmeId,
                ]);
            }

            $stmt = $this->db->prepare(
                'UPDATE programmes SET ProgrammeName = ?, LevelID = ?, ProgrammeLeaderID = ?, Description = ?, Image = ?, IsPublished = ? WHERE ProgrammeID = ?'
            );
            return $stmt->execute([
                $data['ProgrammeName'],
                $data['LevelID'],
                $data['ProgrammeLeaderID'],
                $data['Description'],
                $data['Image'],
                $data['IsPublished'],
                $programmeId,
            ]);
        }

        if ($hasImageAlt) {
            $stmt = $this->db->prepare(
                'UPDATE programmes SET ProgrammeName = ?, LevelID = ?, ProgrammeLeaderID = ?, Description = ?, Image = ?, ImageAlt = ? WHERE ProgrammeID = ?'
            );

            return $stmt->execute([
                $data['ProgrammeName'],
                $data['LevelID'],
                $data['ProgrammeLeaderID'],
                $data['Description'],
                $data['Image'],
                $data['ImageAlt'] ?? null,
                $programmeId,
            ]);
        }

        $stmt = $this->db->prepare(
            'UPDATE programmes SET ProgrammeName = ?, LevelID = ?, ProgrammeLeaderID = ?, Description = ?, Image = ? WHERE ProgrammeID = ?'
        );

        return $stmt->execute([
            $data['ProgrammeName'],
            $data['LevelID'],
            $data['ProgrammeLeaderID'],
            $data['Description'],
            $data['Image'],
            $programmeId,
        ]);
    }

    public function setPublished(int $programmeId, bool $isPublished): bool
    {
        if (!$this->supportsPublishing()) {
            return true;
        }

        $stmt = $this->db->prepare('UPDATE programmes SET IsPublished = ? WHERE ProgrammeID = ?');
        return $stmt->execute([$isPublished ? 1 : 0, $programmeId]);
    }

    public function delete(int $programmeId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM programmes WHERE ProgrammeID = ?');
        return $stmt->execute([$programmeId]);
    }

    public function supportsPublishing(): bool
    {
        if ($this->hasPublishedColumn !== null) {
            return $this->hasPublishedColumn;
        }

        $stmt = $this->db->query("SHOW COLUMNS FROM programmes LIKE 'IsPublished'");
        $this->hasPublishedColumn = (bool) $stmt->fetch();

        return $this->hasPublishedColumn;
    }

    private function getStaffProfileSelect(string $staffAlias, string $fieldPrefix): string
    {
        if ($this->supportsStaffProfiles()) {
            return "
                {$staffAlias}.JobTitle AS {$fieldPrefix}JobTitle,
                {$staffAlias}.Department AS {$fieldPrefix}Department,
                {$staffAlias}.Bio AS {$fieldPrefix}Bio,
                {$staffAlias}.PhotoUrl AS {$fieldPrefix}PhotoUrl
            ";
        }

        return "
            NULL AS {$fieldPrefix}JobTitle,
            NULL AS {$fieldPrefix}Department,
            NULL AS {$fieldPrefix}Bio,
            NULL AS {$fieldPrefix}PhotoUrl
        ";
    }

    private function supportsStaffProfiles(): bool
    {
        if ($this->hasStaffProfileColumns !== null) {
            return $this->hasStaffProfileColumns;
        }

        $columns = $this->db->query('SHOW COLUMNS FROM staff')->fetchAll(PDO::FETCH_COLUMN, 0);
        $lookup = array_flip($columns);
        $this->hasStaffProfileColumns = isset($lookup['JobTitle'], $lookup['Department'], $lookup['Bio'], $lookup['PhotoUrl']);

        return $this->hasStaffProfileColumns;
    }

    private function supportsProgrammeImageAlt(): bool
    {
        if ($this->hasProgrammeImageAltColumn !== null) {
            return $this->hasProgrammeImageAltColumn;
        }

        $stmt = $this->db->query("SHOW COLUMNS FROM programmes LIKE 'ImageAlt'");
        $this->hasProgrammeImageAltColumn = (bool) $stmt->fetch();

        return $this->hasProgrammeImageAltColumn;
    }

    private function supportsModuleImageAlt(): bool
    {
        if ($this->hasModuleImageAltColumn !== null) {
            return $this->hasModuleImageAltColumn;
        }

        $stmt = $this->db->query("SHOW COLUMNS FROM modules LIKE 'ImageAlt'");
        $this->hasModuleImageAltColumn = (bool) $stmt->fetch();

        return $this->hasModuleImageAltColumn;
    }

    private function supportsProgrammeFullText(): bool
    {
        if ($this->hasProgrammeFullTextIndex !== null) {
            return $this->hasProgrammeFullTextIndex;
        }

        $stmt = $this->db->query("SHOW INDEX FROM programmes WHERE Key_name = 'ft_programmes_name_desc'");
        $rows = $stmt->fetchAll();

        if (empty($rows)) {
            $this->hasProgrammeFullTextIndex = false;
            return $this->hasProgrammeFullTextIndex;
        }

        foreach ($rows as $row) {
            if (strtoupper((string) ($row['Index_type'] ?? '')) === 'FULLTEXT') {
                $this->hasProgrammeFullTextIndex = true;
                return $this->hasProgrammeFullTextIndex;
            }
        }

        $this->hasProgrammeFullTextIndex = false;
        return $this->hasProgrammeFullTextIndex;
    }

    private function buildBooleanFullTextQuery(string $search): string
    {
        $tokens = preg_split('/\s+/', trim($search)) ?: [];
        $queryParts = [];

        foreach ($tokens as $token) {
            $clean = preg_replace('/[^[:alnum:]]+/u', '', (string) $token);
            if ($clean === null || $clean === '') {
                continue;
            }

            if (mb_strlen($clean) < 3) {
                continue;
            }

            $queryParts[] = '+' . $clean . '*';
        }

        return implode(' ', $queryParts);
    }
}
