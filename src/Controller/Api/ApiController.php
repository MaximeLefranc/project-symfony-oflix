<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    /**
     * Send an 200 response with datas
     *
     * @param mixed $data
     * @param array $groups
     * @return JsonResponse
     */
    protected function json200($data, $groups = []): JsonResponse
    {
        return $this->json(
            $data,
            Response::HTTP_OK,
            [],
            ['groups' => $groups]
        );
    }

    /**
     * Send an 201 response with data
     *
     * @param mixed $data
     * @param string $roadToRedirect name of the road to redirect
     * @param string $roadParam which is the road param for this road => id? slug? ..etc
     * @param string|int $slugOrId slug or id object
     * @param array $groups serrialisation groups
     * @return JsonResponse
     */
    protected function json201(
        mixed $data,
        string $roadToRedirect,
        string $roadParam,
        string|int $slugOrId,
        array $groups
    ): JsonResponse {
        return $this->json(
            $data,
            Response::HTTP_CREATED,
            [
                // I'm not sure it work !!
                'Location' => $this->generateUrl($roadToRedirect, [$roadParam => $slugOrId])
            ],
            [
                'groups' => $groups
            ]
        );
    }

    /**
     * Send an 206 response with data
     *
     * @param mixed $data
     * @param string $roadToRedirect name of the road to redirect
     * @param string $roadParam which is the road param for this road => id? slug? ..etc
     * @param string|int $slugOrId slug or id object
     * @param array $groups serrialisation groups
     * @return JsonResponse
     */
    protected function json206(
        mixed $data,
        string $roadToRedirect,
        string $roadParam,
        string|int $slugOrId,
        array $groups
    ): JsonResponse {
        return $this->json(
            $data,
            Response::HTTP_PARTIAL_CONTENT,
            [
                // I'm not sure it work !!
                'Location' => $this->generateUrl($roadToRedirect, [$roadParam => $slugOrId])
            ],
            [
                'groups' => $groups
            ]
        );
    }

    /**
     * Send an 404 response with error message
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function json404(string $message): JsonResponse
    {
        return $this->json(
            [
                'error' => $message
            ],
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Send an 422 response with error message
     *
     * @param string|object $message
     * @return JsonResponse
     */
    protected function json422(string|object $message): JsonResponse
    {
        return $this->json(
            [
                'error' => $message
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * Send an 400 response with error message
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function json400(string $message): JsonResponse
    {
        return $this->json(
            [
                'error' => $message
            ],
            Response::HTTP_BAD_REQUEST
        );
    }
}
