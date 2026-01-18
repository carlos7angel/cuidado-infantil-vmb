<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\Educator\FormEducatorRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Educator\GetEducatorsJsonDataTableRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Educator\StoreEducatorRequest;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\Educator\Actions\CreateEducatorWebAction;
use App\Containers\Monitoring\Educator\Actions\GenerateEducatorsReportAction;
use App\Containers\Monitoring\Educator\Actions\GetEducatorsJsonDataTableAction;
use App\Containers\Monitoring\Educator\Actions\UpdateEducatorWebAction;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Containers\Monitoring\Educator\Tasks\FindEducatorByIdTask;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

final class EducatorController extends WebController
{
    public function manage(): View
    {
        $page_title = 'Gestión de Educadores';

        return view('frontend@administrator::educator.manage', compact('page_title'));
    }

    public function listJsonDataTable(GetEducatorsJsonDataTableRequest $request): JsonResponse
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

    public function generateEducatorsReport()
    {
        try {
            return app(GenerateEducatorsReportAction::class)->run();
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $educator = Educator::with('user')->findOrFail($id);

            if (!$educator->user) {
                return response()->json([
                    'success' => false,
                    'message' => 'El educador no tiene una cuenta de usuario asociada.',
                ], 422);
            }

            $user = $educator->user;
            $user->active = !$user->active;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => $user->active
                    ? 'La cuenta del educador ha sido activada correctamente.'
                    : 'La cuenta del educador ha sido desactivada correctamente.',
                'active' => $user->active,
                'status_label' => $user->active ? 'Activo' : 'Inactivo',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

