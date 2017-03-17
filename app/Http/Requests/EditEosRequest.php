<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class EditEosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      // if (Auth::User()->can('eosAdmin')) {
        // dd('Hi mom');
        // return true;
      // }
      // \App::abort(403, 'Sup');

      if (Auth::User()->can('eosAdmin') || Auth::User()->can('eosGuest')) {
        return true;
      }
      \App::abort(403, 'Sup');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
      $rules['name'] = 'required';
      $rules['description'] = 'required';
      if ($this->project_id == 0) {
        $rules['job_num'] = 'required';
      }else{
        $this->merge(['job_num' => '']);
      }
      // if ($this->has('needed_by'))
      //  {
      //
      //  }

      if ($this->needed_by == '') {
        $eos = $this->except('needed_by');
        $this->replace($eos);
      }else{
        $this->merge(['needed_by' => date('Y-m-d', strtotime($this->needed_by))]);
      }
      return $rules;

    }

    public function messages(){
      return [
        'name.required' => 'Name is a required field',
        'job_num.required' => 'Job Number is a required field',
        'description.required' => 'Description is a required field',
        'dimX.required' => 'All three(3) dimensions are required, X is not filled',
        'dimY.required' => 'All three(3) dimensions are required, Y is not filled',
        'dimZ.required' => 'All three(3) dimensions are required, Z is not filled',
        'number_of_parts.required' => 'Number of Parts is a required field',
        'dimX.numeric' => 'Each dimension should be entered as a numeric value. X is not a number',
        'dimY.numeric' => 'Each dimension should be entered as a numeric value. Y is not a number',
        'dimZ.numeric' => 'Each dimension should be entered as a numeric value. Z is not a number',
        'number_of_parts.numeric' => 'Number of Parts should be entered aas a numeric value',
      ];
    }
}
