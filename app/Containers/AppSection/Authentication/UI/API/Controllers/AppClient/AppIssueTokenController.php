<?php

namespace App\Containers\AppSection\Authentication\UI\API\Controllers\AppClient;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\Authentication\Actions\Api\AppClient\AppIssueTokenAction;
use App\Containers\AppSection\Authentication\UI\API\Requests\AppClient\AppIssueTokenRequest;
use App\Containers\AppSection\Authentication\UI\API\Transformers\PasswordTokenTransformer;
use App\Containers\AppSection\Authentication\Values\UserCredential;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class AppIssueTokenController extends ApiController
{
    public function __invoke(AppIssueTokenRequest $request, AppIssueTokenAction $action): JsonResponse
    {
        $result = $action->run(
            UserCredential::createFrom($request),
        );

        return Response::create($result, PasswordTokenTransformer::class)->ok()
            ->withCookie($result->refreshToken->asCookie());
    }
}
