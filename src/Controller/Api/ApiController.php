<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    protected function json200($data, $groups = [])
    {
        return $this->json(
            $data,
            Response::HTTP_OK,
            [],
            ['groups' => $groups]
        );
    }
}
