<?php
require_once ('Model.php');

/**
 * Color model
 */
class ColorModel extends Model
{
    /**
     * Return colors list
     * 
     * @return array
     */
    public function getColors()
    {
        $query = 'SELECT * FROM colors ORDER BY id ASC';
        $shm = self::db()->prepare($query);
        $shm->execute();

        return $shm->fetchAll(PDO::FETCH_ASSOC);
    }
}
