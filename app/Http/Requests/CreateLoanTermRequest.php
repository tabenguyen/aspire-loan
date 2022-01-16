<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class CreateLoanTermRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'apr' => 'required|integer',
            'length' => 'required|integer',
            'fee' => 'required|integer',
            'interest_type' => 'required|integer|in:0,1',
        ];
    }

    /**
     * Return parameters
     *
     * @return array
     */
    public function parameters(): array
    {
        $all = $this->all();
        return [
            'apr' => Arr::get($all, 'apr'),
            'length' => Arr::get($all, 'length'),
            'fee' => Arr::get($all, 'fee'),
            'interest_type' => Arr::get($all, 'interest_type'),
        ];
    }
}
