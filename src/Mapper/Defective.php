<?php

namespace App\Mapper;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

interface Defective
{
    public function handle(Exception $exception): JsonResponse;
}