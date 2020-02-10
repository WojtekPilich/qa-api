<?php

namespace App\Mapper;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

interface Catchable
{
    public function catch(Exception $exception): JsonResponse;
}