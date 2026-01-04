<?php

namespace App\Containers\Monitoring\Room\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Room\Actions\ListRoomsByChildcareCenterAction;
use App\Containers\Monitoring\Room\UI\API\Requests\ListRoomsByChildcareCenterRequest;
use App\Containers\Monitoring\Room\UI\API\Transformers\RoomTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListRoomsByChildcareCenterController extends ApiController
{
    public function __invoke(ListRoomsByChildcareCenterRequest $request, ListRoomsByChildcareCenterAction $action): JsonResponse
    {
        $rooms = $action->run($request);

        return Response::create($rooms, RoomTransformer::class)->ok();
    }
}