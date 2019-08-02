<?php
require_once ('Model.php');

/**
 * Vote model
 */
class VoteModel extends Model
{
    /**
     * Return votes by color ID
     * 
     * @param int $colorId
     * @return array
     */
    public function getVotesByColor($colorId)
    {
        $colorId = (int) $colorId;

        $query = 'SELECT * FROM votes
            WHERE color_id = :colorId
            ORDER BY id ASC';
        $shm = self::db()->prepare($query);
        $shm->execute([':colorId' => $colorId]);

        return $shm->fetchAll(PDO::FETCH_ASSOC);
    }
}
