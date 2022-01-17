<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractStatusResource extends JsonResource
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
            'debtAmount' => $this->getDebtAmount(),
            'repaymentAmount' => $this->getRepaymentAmount(),
            'fee' => $this->getFee(),
            'interest' => $this->getInterest(),
        ];
    }
}
