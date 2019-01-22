<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetRequest extends FormRequest
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
     * Validation rules must be as specified in the jQuery Datatable.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'draw' => 'required|numeric',
            'start' => 'required|numeric',
            'length' => 'required|numeric',
            'columns' => 'required|array',
            'order' => 'required|array',
            'search' => 'required|array',
        ];
    }


    /**
     * Parse request query parameters and prepare it for usage.
     *
     * @return array
     */
    public function parseQuery()
    {
        $draw = intval($this['draw']);
        $start = $this['start'];
        $perPage = $this['length'];
        $columns = array_map(function ($column) {
            if ($column['data']) {
                return $column['data'];
            }
        }, $this['columns']);
        array_pop($columns);
        $sortColumn = $columns[$this['order'][0]['column']];
        $sortDirection = $this['order'][0]['dir'];
        $searchValue = $this['search']['value'];

        return [
          'draw' => $draw,
          'start' => $start,
          'perPage' => $perPage,
          'columns' => $columns,
          'sortColumn' => $sortColumn,
          'sortDirection' => $sortDirection,
          'searchValue' => $searchValue
        ];
    }
}
