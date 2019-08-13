<?php
namespace app\models;

/**
 * City model
 */
class City extends Model
{
    protected $table = 'cities';

    /**
     * List of fields
     * @var array
     */
    protected $fields = [
        'id' => [],
        'country_id' => [
            'required' => true,
        ],
        'name' => [
            'required' => true,
        ],
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

        $query = 'SELECT cities.*, countries.name as country_name'
            . ' FROM ' . $this->table
            . ' JOIN countries ON (cities.country_id = countries.id)'
            . ' ORDER BY cities.id ASC'
            . ' LIMIT :limit OFFSET :offset';

        $stmt = $this->getDb()->prepare($query);

        foreach ($params as $name => $value) {
            $stmt->bindValue(":{$name}", $value, self::getBindValueType($value));
        }

        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}