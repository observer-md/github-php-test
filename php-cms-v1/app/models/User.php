<?php
namespace app\models;


/**
 * User model class
 */
class User extends Model
{
    /**
     * Table name
     */
    protected $table = 'users';

    /**
     * List of fields
     * @var array
     */
    protected $fields = [
        'id' => [],
        'first_name' => [],
        'last_name' => [],
        'username' => [],
        'password' => [],
        'email' => [],
        'token' => [],
        'status' => [],
        'created_date' => [],
        'updated_date' => [],
    ];

    /**
     * Find user by username
     * 
     * @param string $username  Username
     * 
     * @return array|null
     * @throws \Exception
     */
    public function findByUsername($username)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE username = :username';

        $stmt = $this->getDb()->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    
    /**
     * Generate token
     * @return string
     */
    public static function generateToken()
    {
        return sha1(uniqid(rand(), true));
    }


    /**
     * Auth user check and returns user data
     * 
     * @param string $username     Username
     * @param string $password     Password
     * 
     * @return array
     * @throws \Exception
     */
    public function auth($username, $password)
    {
        // fild user
        $user = $this->findByUsername($username);
        if (!$user) {
            throw new \Exception('Incorrect Username or Password.');
        }
        
        // check password
        if ($user['password'] !== $password) {
            throw new \Exception('Incorrect Username or Password.');
        }
        
        // update token
        $token = User::generateToken();
        $user['token'] = $token;
        
        try {
            $this->update($user['id'], ['token' => $token]);
        } catch (\Exception $e) {
            throw new \Exception('Some error occurred. Try again.');
        }
        
        return $user;
    }
}