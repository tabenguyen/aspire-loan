<?php

namespace App\Services;

use App\Http\Requests\CreateLoanTermRequest;
use App\Models\LoanTerm;
use Illuminate\Support\Facades\Log;

abstract class BaseService
{
    private $queryBuider;

    public function __construct()
    {
        $this->queryBuider = $this->createQueryBuilder();
    }

    abstract public function createQueryBuilder();

    public function search($params = [])
    {
        // Basic search
        return $this->queryBuider->where($params);
    }

    public function find($id = null, $idColum = 'id')
    {
        return $this->queryBuider->where($idColum, $id)->first();
    }
    public function save($data, $id = null, $idColum = 'id') {
        if(!empty($id)) {
            $model = $this->queryBuider->where($idColum, $id)->first();
            $model->update($data);
        } else {
            $model = $this->queryBuider->create($data);
        }
        return $model;
    }
}
