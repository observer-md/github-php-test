<?php
namespace app\models;

/**
 * Country model
 */
class Country extends Model
{
    protected $table = 'countries';

    /**
     * List of fields
     * @var array
     */
    protected $fields = [
        'id' => [],
        'code' => [
            'required' => true,
        ],
        'name' => [
            'required' => true,
        ],
    ];
}