<?php

namespace App\Http\Requests\Sample;

use App\Interfaces\Crud\ICreateRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest implements ICreateRequest
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
            'name'=>'required',
            'location_id'=>'required',
        ];
    }

    function getData()
    {
        return $this->except('id');
    }
}
