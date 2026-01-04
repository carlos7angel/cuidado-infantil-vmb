<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\Educator\FormEducatorRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Educator\GetEducatorsJsonDataTableRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Educator\StoreEducatorRequest;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\Educator\Actions\CreateEducatorWebAction;
use App\Containers\Monitoring\Educator\Actions\GetEducatorsJsonDataTableAction;
use App\Containers\Monitoring\Educator\Actions\UpdateEducatorWebAction;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Containers\Monitoring\Educator\Tasks\FindEducatorByIdTask;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Contracts\View\View;

final class EducatorController extends WebController
{
    public function manage(): View
    {
        $page_title = 'Gestión de Educadores';

        return view('frontend@administrator::educator.manage', compact('page_title'));
    }

    public function listJsonDataTable(GetEducatorsJsonDataTableRequest $request)
    {
        try {
            $data = app(GetEducatorsJsonDataTableAction::class)->run($request);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function form_create(FormEducatorRequest $request): View
    {
        $page_title = "Nuevo Educador";
        $educator = new Educator();
        $childcare_centers = ChildcareCenter::orderBy('name')->get();

        return view('frontend@administrator::educator.form', compact('page_title', 'educator', 'childcare_centers'));
    }

    public function form_edit(FormEducatorRequest $request): View
    {
        $page_title = "Editar Educador";
        $educator = app(FindEducatorByIdTask::class)->run($request->id);
        $educator->load('childcareCenters', 'user');
        $childcare_centers = ChildcareCenter::orderBy('name')->get();

        return view('frontend@administrator::educator.form', compact('page_title', 'educator', 'childcare_centers'));
    }

    public function store(StoreEducatorRequest $request)
    {
        try {
            if ($request->has('id') && $request->id) {
                $educator = app(UpdateEducatorWebAction::class)->run($request, $request->id);
                $message = 'Educador actualizado exitosamente';
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'redirect' => route('admin.educator.form_edit', ['id' => $educator->id])
                ]);
            } else {
                $result = app(CreateEducatorWebAction::class)->run($request);
                $educator = $result['educator'];
                $password = $result['password'];
                $message = 'Educador creado exitosamente. Contraseña generada: ' . $password;
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'password' => $password, // Include password in response
                    'redirect' => route('admin.educator.form_edit', ['id' => $educator->id])
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}

