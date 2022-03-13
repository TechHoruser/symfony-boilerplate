<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\GetUsers\GetUsersQuery;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/user", methods={"GET"})
 *
 * @OpenApi\Annotations\Get (
 *     path="/user",
 *     summary="Get Users",
 *     tags={"Users"},
 *     @OpenApi\Annotations\Parameter (
 *         name="page",
 *         in="query",
 *         description="The number of page to show"
 *     )
 * )
 */
class GetUsersController extends AbstractQueryController
{
    public function __invoke(): JsonResponse
    {
        $results = $this->dispatch(new GetUsersQuery(
            $this->generatePaginationPropertiesByQueryParams(),
            $this->request->query->all('filters')
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
