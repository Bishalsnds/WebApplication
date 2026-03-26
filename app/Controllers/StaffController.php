<?php

namespace App\Controllers;

use App\Models\Staff;

class StaffController
{
    private Staff $staffModel;

    public function __construct()
    {
        $this->staffModel = new Staff();
    }

    public function index(): void
    {
        $staffMembers = $this->staffModel->getAllStaff();
        $selectedStaffId = isset($_GET['staff_id']) && $_GET['staff_id'] !== '' ? (int) $_GET['staff_id'] : 0;

        $selectedStaff = null;
        $modules = [];
        $programmes = [];

        if ($selectedStaffId > 0) {
            $selectedStaff = $this->staffModel->getById($selectedStaffId);

            if ($selectedStaff !== null) {
                $modules = $this->staffModel->getModulesLedByStaff($selectedStaffId);
                $programmes = $this->staffModel->getProgrammesByStaff($selectedStaffId);
            }
        }

        require __DIR__ . '/../Views/staff/dashboard.php';
    }
}
