<?php declare(strict_types=1);

namespace App\Mapper;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

interface Mappable
{
    public function map($data, int $responseCode = Response::HTTP_OK): JsonResponse;
}