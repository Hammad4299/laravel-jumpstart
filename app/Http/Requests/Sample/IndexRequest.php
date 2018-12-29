<?php

namespace App\Http\Requests\Sample;

use App\Interfaces\Crud\IIndexRequest;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest implements IIndexRequest
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

    function getFilters()
    {
        return [
            'location_id'=>$this->get('location_id')
        ];
    }

    function getOptions()
    {
        return [
            'paginate'=>$this->get('limit'),
            'load_kiosk_ids'=>$this->get('load_kiosk_ids') == true
        ];
    }
}
