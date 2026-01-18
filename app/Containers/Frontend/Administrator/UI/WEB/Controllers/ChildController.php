<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\Child\GetChildrenJsonDataTableRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Child\ShowChildRequest;
use App\Containers\Monitoring\Child\Actions\GenerateChildrenReportAction;
use App\Containers\Monitoring\Child\Actions\GetChildrenJsonDataTableAction;
use App\Containers\Monitoring\Child\Tasks\FindChildByIdTask;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Contracts\View\View;

final class ChildController extends WebController
{
    public function manage(): View
    {
        $page_title = 'Gestión de Infantes Inscritos';
        $childcare_centers = ChildcareCenter::orderBy('name')->get();

        return view('frontend@administrator::child.manage', compact('page_title', 'childcare_centers'));
    }

    public function listJsonDataTable(GetChildrenJsonDataTableRequest $request)
    {
        try {
            $data = app(GetChildrenJsonDataTableAction::class)->run($request);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(ShowChildRequest $request): View
    {
        $page_title = 'Detalle del Infante';
        $child = app(FindChildByIdTask::class)->run($request->id);
        
        // Load all related data
        $child->load([
            'medicalRecord',
            'socialRecord.familyMembers',
            'enrollments' => function ($query) {
                $query->with(['childcareCenter', 'room'])->orderBy('created_at', 'desc');
            },
            'activeEnrollment.childcareCenter',
            'activeEnrollment.room',
            'activeEnrollment.admissionRequestFile',
            'activeEnrollment.commitmentFile',
            'activeEnrollment.birthCertificateFile',
            'activeEnrollment.vaccinationCardFile',
            'activeEnrollment.parentIdFile',
            'activeEnrollment.workCertificateFile',
            'activeEnrollment.utilityBillFile',
            'activeEnrollment.homeSketchFile',
            'activeEnrollment.residenceCertificateFile',
            'activeEnrollment.pickupAuthorizationFile'
        ]);

        return view('frontend@administrator::child.show', compact('page_title', 'child'));
    }

    public function generateChildrenReport()
    {
        try {
            return app(GenerateChildrenReportAction::class)->run();
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }
}

