<?php
namespace App\Models;

/**
 * 
 * 
 * @package     Core\Support
 * @author      GEE
 */
class Country extends \Core\Model
{
    protected $objId        = 'co';
    protected $table        = 'countries';
    protected $primaryKey   = 'id';

    // protected $createdAt    = 'created_date';
    // protected $updatedAt    = 'updated_date';
    
    

    /**
     * Validation rules
     * @var array
     */
    protected function rules()
    {
        return [
            'id'     => ['field' => 'id', 'label' => 'ID', 'type' => 'numeric'],
            'code'   => ['field' => 'code', 'label' => 'Code', 'type' => 'string', 'required' => true, 'max' => 4],
            'name'   => ['field' => 'name', 'label' => 'Name', 'type' => 'string', 'required' => true, 'max' => 24],
            'status' => ['field' => 'status', 'label' => 'Status', 'type' => 'numeric', 'default' => 1],
        ];
    }


    public function cities()
    {
        // ['model' => 'City', 'primaryKey' => 'id', 'foregnKey' => 'country_id']
        // ['model' => 'City', 'pk' => 'id', 'fk' => 'country_id']
        return City::init()->where('country_id', '=', $this->id)->findAll();
    }
}
