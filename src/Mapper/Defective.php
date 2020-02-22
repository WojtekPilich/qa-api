<?php declare(strict_types=1);

namespace App\Mapper;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

interface Defective
{
    public function handle(Exception $exception): JsonResponse;
}