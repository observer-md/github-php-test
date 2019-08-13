<?php
namespace app\models;

/**
 * Person model
 */
class Person extends Model
{
    protected $table = 'persons';

    protected $fields = [
        'id' => [],
        'first_name' => [
            'required' => true,
        ],
        'last_name' => [
            'required' => true,
        ],
        'country_id' => [
            'required' => true,
        ],
        'city_id' => [
            'required' => true,
        ],
        'address' => [],
        'phone' => [],
        'status' => [],
        'created_date' => [],
        'updated_date' => [],
    ];


    /**
     * Find all records
     * 
     * @return array
     */
    public function findAll(array $params = [])
    {
        $params += [
            'limit'  => $this->limit,
            'offset' => 0,
        ];

        $query = 'SELECT persons.*, countries.name AS country_name, cities.name AS city_name'
            . ' FROM ' . $this->table
            . ' JOIN countries ON (persons.country_id = countries.id)'
            . ' JOIN cities ON (persons.city_id = cities.id)'
            . ' ORDER BY persons.id ASC'
            . ' LIMIT :limit OFFSET :offset';

        $stmt = $this->getDb()->prepare($query);

        foreach ($params as $name => $value) {
            $stmt->bindValue(":{$name}", $value, self::getBindValueType($value));
        }

        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}