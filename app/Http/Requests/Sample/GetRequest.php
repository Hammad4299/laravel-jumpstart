<?php

namespace App\Http\Requests\Sample;

use App\Interfaces\Crud\IGetRequest;
use Illuminate\Foundation\Http\FormRequest;

class GetRequest extends FormRequest implements IGetRequest
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
            //
        ];
    }


    function getId()
    {
        return $this->route()->parameter('id');
    }
}
