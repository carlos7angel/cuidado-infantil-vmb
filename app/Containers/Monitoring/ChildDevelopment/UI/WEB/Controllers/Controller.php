<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildDevelopment\Actions\CreateChildDevelopmentAction;
use App\Containers\Monitoring\ChildDevelopment\Actions\DeleteChildDevelopmentAction;
use App\Containers\Monitoring\ChildDevelopment\Actions\FindChildDevelopmentByIdAction;
use App\Containers\Monitoring\ChildDevelopment\Actions\ListChildDevelopmentsAction;
use App\Containers\Monitoring\ChildDevelopment\Actions\UpdateChildDevelopmentAction;
use App\Containers\Monitoring\ChildDevelopment\UI\WEB\Requests\CreateChildDevelopmentRequest;
use App\Containers\Monitoring\ChildDevelopment\UI\WEB\Requests\DeleteChildDevelopmentRequest;
use App\Containers\Monitoring\ChildDevelopment\UI\WEB\Requests\EditChildDevelopmentRequest;
use App\Containers\Monitoring\ChildDevelopment\UI\WEB\Requests\FindChildDevelopmentByIdRequest;
use App\Containers\Monitoring\ChildDevelopment\UI\WEB\Requests\ListChildDevelopmentsRequest;
use App\Containers\Monitoring\ChildDevelopment\UI\WEB\Requests\StoreChildDevelopmentRequest;
use App\Containers\Monitoring\ChildDevelopment\UI\WEB\Requests\UpdateChildDevelopmentRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class Controller extends WebController
{
    public function index(ListChildDevelopmentsRequest $request, ListChildDevelopmentsAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }

    public function show(FindChildDevelopmentByIdRequest $request, FindChildDevelopmentByIdAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }

    public function create(CreateChildDevelopmentRequest $request): View
    {
        return view('placeholder');
    }

    public function store(StoreChildDevelopmentRequest $request, CreateChildDevelopmentAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }

    public function edit(EditChildDevelopmentRequest $request): View
    {
        return view('placeholder');
    }

    public function update(UpdateChildDevelopmentRequest $request, UpdateChildDevelopmentAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }

    public function destroy(DeleteChildDevelopmentRequest $request, DeleteChildDevelopmentAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
