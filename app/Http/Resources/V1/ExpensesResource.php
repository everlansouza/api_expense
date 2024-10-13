<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpensesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Expenses' =>[
                'id' => $this->id,
                'value' => "R$ " . number_format($this->value, 2,',','.'),  
                'description' => $this->description,  
                'date_expenses' => $this->date_expenses,
            ],
            'user' =>[
                'id' => $this->user->id,
                'name' => $this->user->name,  
                'email' => $this->user->email,  
            ]
        ];
    }
}
