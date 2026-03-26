<?php
// app/Models/Interest.php

namespace App\Models;

use App\Core\Database;
use PDO;

class Interest
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function registerInterest(int $programmeId, string $studentName, string $email): bool
    {
        if ($this->hasInterest($programmeId, $email)) {
            return true;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO interestedstudents (ProgrammeID, StudentName, Email) VALUES (?, ?, ?)'
        );

        return $stmt->execute([$programmeId, $studentName, $email]);
    }

    public function hasInterest(int $programmeId, string $email): bool
    {
        $stmt = $this->db->prepare(
            'SELECT InterestID FROM interestedstudents WHERE ProgrammeID = ? AND Email = ? LIMIT 1'
        );
        $stmt->execute([$programmeId, $email]);

        return (bool) $stmt->fetch();
    }

    public function getByEmail(string $email): array
    {
        $sql = "
            SELECT
                i.InterestID,
                i.StudentName,
                i.Email,
                i.RegisteredAt,
                p.ProgrammeID,
                p.ProgrammeName,
                l.LevelName
            FROM interestedstudents i
            INNER JOIN programmes p ON p.ProgrammeID = i.ProgrammeID
            LEFT JOIN levels l ON l.LevelID = p.LevelID
            WHERE i.Email = ?
            ORDER BY i.RegisteredAt DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->fetchAll();
    }

    public function withdrawInterest(int $interestId, string $email): bool
    {
        $stmt = $this->db->prepare('DELETE FROM interestedstudents WHERE InterestID = ? AND Email = ?');
        return $stmt->execute([$interestId, $email]);
    }

    public function getMailingList(?int $programmeId = null): array
    {
        $sql = "
            SELECT
                i.InterestID,
                i.ProgrammeID,
                i.StudentName,
                i.Email,
                i.RegisteredAt,
                p.ProgrammeName,
                l.LevelName
            FROM interestedstudents i
            INNER JOIN programmes p ON p.ProgrammeID = i.ProgrammeID
            LEFT JOIN levels l ON l.LevelID = p.LevelID
        ";

        $params = [];
        if ($programmeId !== null && $programmeId > 0) {
            $sql .= ' WHERE i.ProgrammeID = ?';
            $params[] = $programmeId;
        }

        $sql .= ' ORDER BY i.RegisteredAt DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getStudentProgrammeMatrix(): array
    {
        $sql = "
            SELECT
                i.Email,
                MAX(i.StudentName) AS StudentName,
                GROUP_CONCAT(DISTINCT p.ProgrammeName ORDER BY p.ProgrammeName SEPARATOR ', ') AS InterestedProgrammes
            FROM interestedstudents i
            INNER JOIN programmes p ON p.ProgrammeID = i.ProgrammeID
            GROUP BY i.Email
            ORDER BY StudentName ASC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function deleteById(int $interestId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM interestedstudents WHERE InterestID = ?');
        return $stmt->execute([$interestId]);
    }

    public function removeDuplicateInterests(): int
    {
        $sql = "
            DELETE i1 FROM interestedstudents i1
            INNER JOIN interestedstudents i2
                ON i1.ProgrammeID = i2.ProgrammeID
                AND i1.Email = i2.Email
                AND i1.InterestID > i2.InterestID
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function removeInvalidInterests(): int
    {
        $sql = "
            DELETE FROM interestedstudents
            WHERE Email IS NULL
               OR TRIM(Email) = ''
               OR Email NOT REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\\\.[A-Za-z]{2,}$'
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->rowCount();
    }
}
