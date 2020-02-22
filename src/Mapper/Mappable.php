<?php declare(strict_types=1);

namespace App\Mapper;

use Symfony\Component\HttpFoundation\JsonResponse;

interface Mappable
{
    public function map($valueObject): JsonResponse;
}