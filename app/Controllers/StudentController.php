<?php
// app/Controllers/StudentController.php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Interest;
use App\Models\Programme;

class StudentController
{
    private Programme $programmeModel;
    private Interest $interestModel;

    public function __construct()
    {
        $this->programmeModel = new Programme();
        $this->interestModel = new Interest();
    }

    public function home(): void
    {
        header('Location: /WebApplication/public/programmes');
        exit;
    }

    public function programmes(): void
    {
        $selectedLevel = isset($_GET['level']) && $_GET['level'] !== '' ? (int) $_GET['level'] : null;
        $search = trim($_GET['q'] ?? '');

        $programmes = $this->programmeModel->getPublishedProgrammes($selectedLevel, $search);
        $levels = $this->programmeModel->getLevels();

        require __DIR__ . '/../Views/student/programmes.php';
    }

    public function programmeDetail(): void
    {
        $programmeId = (int) ($_GET['id'] ?? 0);
        $programme = $this->programmeModel->getById($programmeId);

        if (!$programme) {
            http_response_code(404);
            die('Programme not found');
        }

        if ($this->programmeModel->supportsPublishing() && (int) $programme['IsPublished'] !== 1) {
            http_response_code(404);
            die('Programme not found');
        }

        require __DIR__ . '/../Views/student/programme-detail.php';
    }

    public function showInterestForm(): void
    {
        $programmeId = (int) ($_GET['programme_id'] ?? 0);
        $programme = $this->programmeModel->getById($programmeId);

        if (!$programme) {
            http_response_code(404);
            die('Programme not found');
        }

        if ($this->programmeModel->supportsPublishing() && (int) $programme['IsPublished'] !== 1) {
            http_response_code(404);
            die('Programme not found');
        }

        $error = null;
        $success = isset($_GET['success']) && $_GET['success'] === '1';
        require __DIR__ . '/../Views/student/interest-form.php';
    }

    public function submitInterest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Method not allowed');
        }

        if (!Auth::verifyCsrfToken($_POST['_token'] ?? null)) {
            http_response_code(400);
            die('Invalid form token');
        }

        $programmeId = (int) ($_POST['programme_id'] ?? 0);
        $studentName = trim($_POST['student_name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        $programme = $this->programmeModel->getById($programmeId);
        if (!$programme) {
            http_response_code(404);
            die('Programme not found');
        }

        $error = null;
        $success = false;

        if ($studentName === '') {
            $error = 'Please enter your full name.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        }

        if ($error !== null) {
            require __DIR__ . '/../Views/student/interest-form.php';
            return;
        }

        $this->interestModel->registerInterest($programmeId, $studentName, $email);
        header('Location: /WebApplication/public/interest?programme_id=' . $programmeId . '&success=1');
        exit;
    }

    public function manageInterests(): void
    {
        $email = trim($_GET['email'] ?? '');
        $error = null;
        $rows = [];
        $removed = isset($_GET['removed']) && $_GET['removed'] === '1';

        if ($email !== '') {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address.';
            } else {
                $rows = $this->interestModel->getByEmail($email);
            }
        }

        require __DIR__ . '/../Views/student/my-interests.php';
    }

    public function withdrawInterest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Method not allowed');
        }

        if (!Auth::verifyCsrfToken($_POST['_token'] ?? null)) {
            http_response_code(400);
            die('Invalid form token');
        }

        $interestId = (int) ($_POST['interest_id'] ?? 0);
        $email = trim($_POST['email'] ?? '');

        if ($interestId <= 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /WebApplication/public/my-interests');
            exit;
        }

        $this->interestModel->withdrawInterest($interestId, $email);
        header('Location: /WebApplication/public/my-interests?email=' . urlencode($email) . '&removed=1');
        exit;
    }
}
