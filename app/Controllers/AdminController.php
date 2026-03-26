<?php
// app/Controllers/AdminController.php
// Admin controller for programme management

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Interest;
use App\Models\Module;
use App\Models\Programme;

class AdminController
{
    private Programme $programmeModel;
    private Module $moduleModel;
    private Interest $interestModel;

    public function __construct()
    {
        Auth::requireAdmin();
        $this->programmeModel = new Programme();
        $this->moduleModel = new Module();
        $this->interestModel = new Interest();
    }

    /**
     * Show admin dashboard with all programmes
     */
    public function dashboard(): void
    {
        $user = Auth::user();
        $programmes = $this->programmeModel->getAllProgrammesForAdmin();
        $supportsPublishing = $this->programmeModel->supportsPublishing();

        require __DIR__ . '/../Views/admin/dashboard.php';
    }

    /**
     * Show add programme form
     */
    public function viewAddForm(): void
    {
        $levels = $this->programmeModel->getLevels();
        $staffMembers = $this->programmeModel->getStaff();
        $programme = [
            'ProgrammeID' => null,
            'ProgrammeName' => '',
            'LevelID' => '',
            'ProgrammeLeaderID' => '',
            'Description' => '',
            'Image' => '',
            'ImageAlt' => '',
            'IsPublished' => 1,
        ];
        $isEdit = false;
        $error = null;

        require __DIR__ . '/../Views/admin/programme-form.php';
    }

    /**
     * Handle programme addition
     */
    public function addProgramme(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Method not allowed');
        }

        if (!Auth::verifyCsrfToken($_POST['_token'] ?? null)) {
            http_response_code(400);
            die('Invalid form token');
        }

        $input = $this->validateProgrammeInput($_POST);
        if ($input['error'] !== null) {
            $levels = $this->programmeModel->getLevels();
            $staffMembers = $this->programmeModel->getStaff();
            $programme = $input['data'];
            $isEdit = false;
            $error = $input['error'];
            require __DIR__ . '/../Views/admin/programme-form.php';
            return;
        }

        $this->programmeModel->create($input['data']);
        header('Location: /WebApplication/public/admin-dashboard');
        exit;
    }

    /**
     * Show edit programme form
     */
    public function viewEditForm(): void
    {
        $programmeId = (int) ($_GET['id'] ?? 0);
        $programme = $this->programmeModel->getById($programmeId);

        if (!$programme) {
            http_response_code(404);
            die('Programme not found');
        }

        $levels = $this->programmeModel->getLevels();
        $staffMembers = $this->programmeModel->getStaff();
        $isEdit = true;
        $error = null;

        require __DIR__ . '/../Views/admin/programme-form.php';
    }

    /**
     * Handle programme update
     */
    public function editProgramme(): void
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
        if ($programmeId <= 0) {
            http_response_code(400);
            die('Invalid programme ID');
        }

        $input = $this->validateProgrammeInput($_POST);
        if ($input['error'] !== null) {
            $levels = $this->programmeModel->getLevels();
            $staffMembers = $this->programmeModel->getStaff();
            $programme = $input['data'];
            $programme['ProgrammeID'] = $programmeId;
            $isEdit = true;
            $error = $input['error'];
            require __DIR__ . '/../Views/admin/programme-form.php';
            return;
        }

        $this->programmeModel->update($programmeId, $input['data']);
        header('Location: /WebApplication/public/admin-dashboard');
        exit;
    }

    /**
     * Handle programme deletion
     */
    public function deleteProgramme(): void
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
        if ($programmeId > 0) {
            $this->programmeModel->delete($programmeId);
        }

        header('Location: /WebApplication/public/admin-dashboard');
        exit;
    }

    public function toggleProgrammePublish(): void
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
        $isPublished = isset($_POST['is_published']) && (int) $_POST['is_published'] === 1;

        if ($programmeId > 0) {
            $this->programmeModel->setPublished($programmeId, $isPublished);
        }

        header('Location: /WebApplication/public/admin-dashboard');
        exit;
    }

    public function modules(): void
    {
        $modules = $this->moduleModel->getAllWithLeader();
        $programmes = $this->programmeModel->getAllProgrammesForAdmin();
        $staffMembers = $this->programmeModel->getStaff();

        $editModule = null;
        if (isset($_GET['id'])) {
            $editModule = $this->moduleModel->getById((int) $_GET['id']);
        }

        require __DIR__ . '/../Views/admin/modules.php';
    }

    public function addModule(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Method not allowed');
        }

        if (!Auth::verifyCsrfToken($_POST['_token'] ?? null)) {
            http_response_code(400);
            die('Invalid form token');
        }

        $moduleName = trim($_POST['module_name'] ?? '');
        $leaderId = (int) ($_POST['module_leader_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $image = trim($_POST['image'] ?? '');
        $imageAlt = trim($_POST['image_alt'] ?? '');
        $programmeId = (int) ($_POST['programme_id'] ?? 0);
        $year = (int) ($_POST['year'] ?? 1);

        if ($moduleName === '' || $leaderId <= 0) {
            header('Location: /WebApplication/public/admin-modules?error=1');
            exit;
        }

        $moduleId = $this->moduleModel->create([
            'ModuleName' => $moduleName,
            'ModuleLeaderID' => $leaderId,
            'Description' => $description,
            'Image' => $image,
            'ImageAlt' => $imageAlt,
        ]);

        if ($programmeId > 0) {
            $this->moduleModel->assignToProgramme($moduleId, $programmeId, max(1, $year));
        }

        header('Location: /WebApplication/public/admin-modules');
        exit;
    }

    public function editModule(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Method not allowed');
        }

        if (!Auth::verifyCsrfToken($_POST['_token'] ?? null)) {
            http_response_code(400);
            die('Invalid form token');
        }

        $moduleId = (int) ($_POST['module_id'] ?? 0);
        if ($moduleId <= 0) {
            http_response_code(400);
            die('Invalid module ID');
        }

        $moduleName = trim($_POST['module_name'] ?? '');
        $leaderId = (int) ($_POST['module_leader_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $image = trim($_POST['image'] ?? '');
        $imageAlt = trim($_POST['image_alt'] ?? '');

        if ($moduleName === '' || $leaderId <= 0) {
            header('Location: /WebApplication/public/admin-modules?id=' . $moduleId . '&error=1');
            exit;
        }

        $this->moduleModel->update($moduleId, [
            'ModuleName' => $moduleName,
            'ModuleLeaderID' => $leaderId,
            'Description' => $description,
            'Image' => $image,
            'ImageAlt' => $imageAlt,
        ]);

        header('Location: /WebApplication/public/admin-modules');
        exit;
    }

    public function deleteModule(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Method not allowed');
        }

        if (!Auth::verifyCsrfToken($_POST['_token'] ?? null)) {
            http_response_code(400);
            die('Invalid form token');
        }

        $moduleId = (int) ($_POST['module_id'] ?? 0);
        if ($moduleId > 0) {
            $this->moduleModel->delete($moduleId);
        }

        header('Location: /WebApplication/public/admin-modules');
        exit;
    }

    public function mailingList(): void
    {
        $selectedProgrammeId = isset($_GET['programme_id']) && $_GET['programme_id'] !== '' ? (int) $_GET['programme_id'] : null;
        $rows = $this->interestModel->getMailingList($selectedProgrammeId);
        $matrix = $this->interestModel->getStudentProgrammeMatrix();
        $programmes = $this->programmeModel->getAllProgrammesForAdmin();
        $message = trim($_GET['message'] ?? '');

        require __DIR__ . '/../Views/admin/mailing-list.php';
    }

    public function exportMailingList(): void
    {
        $selectedProgrammeId = isset($_GET['programme_id']) && $_GET['programme_id'] !== '' ? (int) $_GET['programme_id'] : null;
        $rows = $this->interestModel->getMailingList($selectedProgrammeId);

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="mailing-list.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['InterestID', 'ProgrammeID', 'ProgrammeName', 'Level', 'StudentName', 'Email', 'RegisteredAt']);

        foreach ($rows as $row) {
            fputcsv($output, [
                $row['InterestID'],
                $row['ProgrammeID'],
                $row['ProgrammeName'],
                $row['LevelName'] ?? '',
                $row['StudentName'],
                $row['Email'],
                $row['RegisteredAt'],
            ]);
        }

        fclose($output);
        exit;
    }

    public function deleteInterest(): void
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
        $selectedProgrammeId = isset($_POST['selected_programme_id']) && $_POST['selected_programme_id'] !== ''
            ? (int) $_POST['selected_programme_id']
            : null;

        if ($interestId > 0) {
            $this->interestModel->deleteById($interestId);
        }

        $redirect = '/WebApplication/public/admin-mailing-list?message=Interest+removed';
        if ($selectedProgrammeId !== null && $selectedProgrammeId > 0) {
            $redirect .= '&programme_id=' . $selectedProgrammeId;
        }

        header('Location: ' . $redirect);
        exit;
    }

    public function removeDuplicateInterests(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Method not allowed');
        }

        if (!Auth::verifyCsrfToken($_POST['_token'] ?? null)) {
            http_response_code(400);
            die('Invalid form token');
        }

        $count = $this->interestModel->removeDuplicateInterests();
        header('Location: /WebApplication/public/admin-mailing-list?message=' . urlencode($count . ' duplicate interest(s) removed'));
        exit;
    }

    public function removeInvalidInterests(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Method not allowed');
        }

        if (!Auth::verifyCsrfToken($_POST['_token'] ?? null)) {
            http_response_code(400);
            die('Invalid form token');
        }

        $count = $this->interestModel->removeInvalidInterests();
        header('Location: /WebApplication/public/admin-mailing-list?message=' . urlencode($count . ' invalid interest(s) removed'));
        exit;
    }

    private function validateProgrammeInput(array $input): array
    {
        $programmeName = trim($input['programme_name'] ?? '');
        $levelId = (int) ($input['level_id'] ?? 0);
        $leaderId = (int) ($input['programme_leader_id'] ?? 0);
        $description = trim($input['description'] ?? '');
        $image = trim($input['image'] ?? '');
        $imageAlt = trim($input['image_alt'] ?? '');
        $isPublished = isset($input['is_published']) ? 1 : 0;

        $data = [
            'ProgrammeName' => $programmeName,
            'LevelID' => $levelId > 0 ? $levelId : null,
            'ProgrammeLeaderID' => $leaderId > 0 ? $leaderId : null,
            'Description' => $description,
            'Image' => $image,
            'ImageAlt' => $imageAlt,
            'IsPublished' => $isPublished,
        ];

        if ($programmeName === '') {
            return ['error' => 'Programme name is required.', 'data' => $data];
        }

        return ['error' => null, 'data' => $data];
    }
}

