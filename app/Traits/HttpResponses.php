<?php

namespace App\Traits;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

trait HttpResponses
{
    public function response(string $menssage, string|int $status, array|Model|JsonResource $data = [])
    {
        return response()->json([
            'menssage' => $menssage,
            'status' => $status,
            'data' => $data
        ], $status);
    }

    public function error(string $menssage, string|int $status, array|MessageBag $erors = [], array $data = [])
    {
        return response()->json([
            'menssage' => $menssage,
            'status' => $status,
            'erros' => $erors,
            'data' => $data
        ], $status);
    }
}
