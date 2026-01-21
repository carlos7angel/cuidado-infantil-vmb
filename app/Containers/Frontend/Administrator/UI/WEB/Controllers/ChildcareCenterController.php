<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\ChildcareCenter\FormChildcareCenterRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\ChildcareCenter\GetChildcareCentersJsonDataTableRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\ChildcareCenter\ManageChildcareCentersRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\ChildcareCenter\StoreChildcareCenterRequest;
use App\Containers\Monitoring\ChildcareCenter\Actions\CreateChildcareCenterWebAction;
use App\Containers\Monitoring\ChildcareCenter\Actions\GetChildcareCentersJsonDataTableAction;
use App\Containers\Monitoring\ChildcareCenter\Actions\UpdateChildcareCenterWebAction;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\ChildcareCenter\Tasks\FindChildcareCenterByIdTask;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Contracts\View\View;

final class ChildcareCenterController extends WebController
{
    public function manage(ManageChildcareCentersRequest $request): View
    {
        $page_title = 'Gestión de Centros Infantiles';

        return view('frontend@administrator::childcare_center.manage', compact('page_title'));
    }

    public function listJsonDataTable(GetChildcareCentersJsonDataTableRequest $request)
    {
        try {
            $data = app(GetChildcareCentersJsonDataTableAction::class)->run($request);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function form_create(FormChildcareCenterRequest $request): View
    {
        $page_title = "Nuevo Centro de Cuidado Infantil";
        $childcare_center = new ChildcareCenter();

        return view('frontend@administrator::childcare_center.form', compact('page_title', 'childcare_center'));
    }

    public function form_edit(FormChildcareCenterRequest $request): View
    {
        $page_title = "Editar Centro de Cuidado Infantil";
        $childcare_center = app(FindChildcareCenterByIdTask::class)->run($request->id);

        return view('frontend@administrator::childcare_center.form', compact('page_title', 'childcare_center'));
    }

    public function store(StoreChildcareCenterRequest $request)
    {
        try {
            if ($request->has('id') && $request->id) {
                $childcare_center = app(UpdateChildcareCenterWebAction::class)->run($request, $request->id);
                $message = 'Centro de cuidado infantil actualizado exitosamente';
            } else {
                $childcare_center = app(CreateChildcareCenterWebAction::class)->run($request);
                $message = 'Centro de cuidado infantil creado exitosamente';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.childcare_center.form_edit', ['id' => $childcare_center->id])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}

