<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApartmentRequest extends FormRequest
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
          'description' => 'required',
          'address' => 'required',
          'mq'=> 'required|numeric',
          'rooms'=> 'required|numeric',
          'beds'=> 'required|numeric',
          'bathrooms'=> 'required|numeric',
          'img'=> 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:4048'
        ];
    }
}
