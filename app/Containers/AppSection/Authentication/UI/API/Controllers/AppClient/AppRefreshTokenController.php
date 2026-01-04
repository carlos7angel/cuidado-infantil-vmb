<?php

namespace App\Containers\AppSection\Authentication\UI\API\Controllers\AppClient;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\Authentication\Actions\Api\AppClient\AppRefreshTokenAction;
use App\Containers\AppSection\Authentication\UI\API\Requests\AppClient\AppRefreshTokenRequest;
use App\Containers\AppSection\Authentication\UI\API\Transformers\PasswordTokenTransformer;
use App\Containers\AppSection\Authentication\Values\RefreshToken;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class AppRefreshTokenController extends ApiController
{
    public function __invoke(AppRefreshTokenRequest $request, AppRefreshTokenAction $action): JsonResponse
    {
        $result = $action->run(RefreshToken::createFrom($request));

        return Response::create($result, PasswordTokenTransformer::class)->ok()
            ->withCookie($result->refreshToken->asCookie());
    }
}
