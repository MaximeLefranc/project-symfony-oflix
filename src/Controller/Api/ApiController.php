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
}
