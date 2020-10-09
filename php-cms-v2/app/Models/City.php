<?php
namespace App\Models;

class City extends \Core\Model
{
    protected $objId = 'ci';
    protected $table = 'cities';
    protected $primaryKey = 'id';
    // protected $primaryKey = ['id', 'country_id'];

    /**
     * Validation rules
     * @return array
     */
    protected function rules()
    {
        return [
            'id' => ['field' => 'id', 'label' => 'ID', 'type' => 'numeric'],
            'country_id' => ['field' => 'country_id', 'label' => 'CountryID', 'type' => 'numeric', 'required' => true],
            'name' => ['field' => 'name', 'label' => 'Name', 'type' => 'string', 'max' => 24, 'required' => true],
        ];
    }


    public function country()
    {
        return Country::init()->where('id', '=', $this->country_id)->find();
    }
}
