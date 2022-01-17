<?php

namespace App\Http\Resources;

use App\Services\ContractService;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'loan_term' => $this->loanTerm,
            'amount' => $this->amount,
            'status' => $this->status,
        ];
    }
}
