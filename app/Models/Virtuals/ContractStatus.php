<?php

namespace App\Models\Virtuals;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractStatus
{
    private int $weekNo;
    private int $debtAmount;
    private int $repaymentAmount;
    private int $fee;
    private int $interest;

    /**
     * Get the value of debtAmount
     */ 
    public function getDebtAmount()
    {
        return $this->debtAmount;
    }

    /**
     * Set the value of debtAmount
     *
     * @return  self
     */ 
    public function setDebtAmount($debtAmount)
    {
        $this->debtAmount = $debtAmount;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  integer
     */ 
    public function getRepaymentAmount()
    {
        return $this->repaymentAmount;
    }

    /**
     * Set undocumented variable
     *
     * @param  integer  $repaymentAmount  Undocumented variable
     *
     * @return  self
     */ 
    public function setRepaymentAmount($repaymentAmount)
    {
        $this->repaymentAmount = $repaymentAmount;

        return $this;
    }

    /**
     * Get the value of fee
     */ 
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * Set the value of fee
     *
     * @return  self
     */ 
    public function setFee($fee)
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * Get the value of interest
     */ 
    public function getInterest()
    {
        return $this->interest;
    }

    /**
     * Set the value of interest
     *
     * @return  self
     */ 
    public function setInterest($interest)
    {
        $this->interest = $interest;

        return $this;
    }

    public function validateAmount(int $amount): bool
    {
        return $amount === ($this->fee + $this->repaymentAmount + $this->interest);
    }
}
