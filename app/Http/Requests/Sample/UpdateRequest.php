<?php

namespace App\Http\Requests\Sample;

use App\Interfaces\Crud\IUpdateRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest implements IUpdateRequest
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

    function getId()
    {
        return $this->get('id');
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
            'location_id'=>'required'
        ];
    }

    function getData()
    {
        return $this->except('id');
    }
}