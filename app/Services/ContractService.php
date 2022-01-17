<?php

namespace App\Services;

use App\Exceptions\TransactionException;
use App\Http\Requests\CreateLoanTermRequest;
use App\Models\Contract;
use App\Models\LoanTerm;
use App\Models\Repayment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Virtuals\ContractStatus;
use App\Services\Contract\AmortizedLoanInterest;
use App\Services\Contract\InterestRepayment;
use App\Services\Contract\NonAmortizedLoanInterest;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContractService extends BaseService
{
    public function createQueryBuilder()
    {
        return Contract::query();
    }
    /**
     * Get processor base on type of interest
     *
     * @param Contract $contract
     * @return InterestRepayment
     */
    public function getInterestProcess(Contract $contract): InterestRepayment
    {
        if ($contract->loanTerm->interest_type == LoanTerm::INTEREST_TYPE_AMOR ) {
            return app(AmortizedLoanInterest::class);
        }
        if ($contract->loanTerm->interest_type == LoanTerm::INTEREST_TYPE_NON_AMOR ) {
            return app(NonAmortizedLoanInterest::class);
        }
    }

    /**
     * User apply to an loan term
     *
     * @param integer $userId
     * @param integer $loanTermId
     * @param integer $amount
     * @param Carbon $startDate
     * @return Contract|null
     */
    public function apply(int $userId, int $loanTermId, int $amount, Carbon $startDate)
    {
        try {
            $contract = Contract::create([
                'user_id' => $userId,
                'loan_term_id' => $loanTermId,
                'amount' => $amount,
                'start_date' => $startDate,
                'status' => Contract::STATUS_PENDING,
            ]);
            return $contract;
        } catch (\Exception $ex) {
            Log::error($ex);
            return null;
        }
    }

    /**
     * Retreive current status of a conztract
     *
     * @param Contract $contract
     * @param Carbon $to
     * @return ContractStatus
     */
    public function getContractStatus(Contract $contract, Carbon $to): ContractStatus
    {
        $debt = $contract->getBalance();
        $fee = $contract->loanTerm->fee - $contract->sumTransaction(Transaction::TYPE_FEE);
        $interest = $this->getInterestProcess($contract)->estimateAmount($contract, $to);
        $status = new ContractStatus();
        $status->setDebtAmount($debt);
        $status->setRepaymentAmount($contract->getRepaymentAmount());
        $status->setFee($fee);
        $status->setInterest($interest);

        return $status;
    }

    /**
     * Undocumented function
     *
     * @param Contract $contract
     * @param User $user
     * @param integer $amount
     * @throws TransactionException
     * @return Repayment|bool
     */
    public function submitRepayment(Contract $contract, User $user, int $amount)
    {
        $contractStatus = $this->getContractStatus($contract, Carbon::now());
        if ($contractStatus->validateAmount($amount)) {
            try {
                DB::beginTransaction();
                $repayment = Repayment::create([
                    'user_id' => $user->id,
                    'contract_id' => $contract->id,
                    'amount' => $amount,
                    'target_before' => $contract->getBalance(),
                    'target_after' => $contract->getBalance() - $contractStatus->getRepaymentAmount(),
                ]);
                // Create fee transation
                if ($contractStatus->getFee() > 0) {
                    Transaction::create([
                        'user_id' => $user->id,
                        'target_id' => $repayment->id,
                        'target_type' => Repayment::class,
                        'amount' => $contractStatus->getFee(),
                        'type' => Transaction::TYPE_FEE,
                        'target_before' => 0,
                        'target_after' => 0,
                    ]);
                }
                // Create repayment transaction
                if ($contractStatus->getRepaymentAmount() > 0) {
                    Transaction::create([
                        'user_id' => $user->id,
                        'target_id' => $repayment->id,
                        'target_type' => Repayment::class,
                        'amount' => $contractStatus->getRepaymentAmount(),
                        'type' => Transaction::TYPE_REPAYMENT,
                        'target_before' => 0,
                        'target_after' => 0,
                    ]);
                }
                // Create interest transaction
                if ($contractStatus->getInterest() > 0) {
                    Transaction::create([
                        'user_id' => $user->id,
                        'target_id' => $repayment->id,
                        'target_type' => Repayment::class,
                        'amount' => $contractStatus->getInterest(),
                        'type' => Transaction::TYPE_INTEREST,
                        'target_before' => 0,
                        'target_after' => 0,
                    ]);
                }
                DB::commit();

                return $repayment;
            } catch(\Exception $ex) {
                DB::rollBack();
                throw new TransactionException('Repayment can not be created. [' . $ex->getMessage() .']');
            }
        }
        return false;
    }

    public function approve(Contract $contract): Contract
    {
        $contract->start_date = Carbon::now();
        $contract->status = Contract::STATUS_ACTIVE;
        $contract->save();

        return $contract;
    }


}
