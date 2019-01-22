<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $guarded = ['id'];

    /**
     * Paginate contacts
     *
     * @param array $columns
     * @param string $sortColumn
     * @param string $sortDirection
     * @param string|null $searchValue
     * @param int $start
     * @param int $perPage
     * @param int $draw
     * @return array
     */
    public static function paginate(
        $columns,
        $sortColumn,
        $sortDirection,
        $searchValue,
        $start,
        $perPage,
        $draw = 0
    ) {
        $query = Contact::select($columns)
            ->orderBy($sortColumn, $sortDirection);

        $recordsTotal = $query->count();

        if ($searchValue) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $searchValue . '%');
            }
            $recordsFiltered = $query->count();
        }

        $results = array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered ?? $recordsTotal,
            "data" => $query
                ->skip($start)
                ->take($perPage)
                ->get()
        );

        return $results;
    }

    /**
     * Set the first letters to uppercase and the rest to lowercase.
     * This mutator will be automatically called when we attempt to save the model.
     *
     * @param $value
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords(strtolower($value));
    }

    /**
     * Set the first letters to uppercase and the rest to lowercase
     * This mutator will be automatically called when we attempt to save the model.
     *
     * @param $value
     */
    public function setLastNameAttribute($value)
    {
        // Set the first letters to uppercase and the rest to lowercase
        $this->attributes['last_name'] = ucwords(strtolower($value));
    }
}
