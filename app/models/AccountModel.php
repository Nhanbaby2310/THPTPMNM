<?php
class AccountModel
{
    private $conn;
    private $table_name = 'account';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAccountByUsername($username)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $username = trim($username);

        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAccountByEmail($email)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $email = trim($email);

        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAccountByProvider($provider, $provider_id)
    {
        $query = "SELECT * FROM {$this->table_name} 
                  WHERE provider = :provider AND provider_id = :provider_id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);

        $provider = trim($provider);
        $provider_id = trim($provider_id);

        $stmt->bindParam(':provider', $provider);
        $stmt->bindParam(':provider_id', $provider_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function save($username, $fullname, $password, $role = 'user')
    {
        if ($this->getAccountByUsername($username)) {
            return false;
        }

        $query = "INSERT INTO {$this->table_name} 
                  (username, fullname, email, password, role, provider, provider_id)
                  VALUES 
                  (:username, :fullname, :email, :password, :role, :provider, :provider_id)";

        $stmt = $this->conn->prepare($query);

        $username = htmlspecialchars(strip_tags(trim($username)));
        $fullname = htmlspecialchars(strip_tags(trim($fullname)));
        $email = null;
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $role = in_array($role, ['admin', 'user']) ? $role : 'user';
        $provider = 'local';
        $provider_id = null;

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':provider', $provider);
        $stmt->bindParam(':provider_id', $provider_id);

        return $stmt->execute();
    }

    public function createSocialAccount($username, $fullname, $email, $provider, $provider_id)
    {
        $query = "INSERT INTO {$this->table_name} 
                  (username, fullname, email, password, role, provider, provider_id)
                  VALUES 
                  (:username, :fullname, :email, :password, :role, :provider, :provider_id)";

        $stmt = $this->conn->prepare($query);

        $username = htmlspecialchars(strip_tags(trim($username)));
        $fullname = htmlspecialchars(strip_tags(trim($fullname)));
        $email = htmlspecialchars(strip_tags(trim($email)));
        $passwordHash = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
        $role = 'user';
        $provider = htmlspecialchars(strip_tags(trim($provider)));
        $provider_id = htmlspecialchars(strip_tags(trim($provider_id)));

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':provider', $provider);
        $stmt->bindParam(':provider_id', $provider_id);

        return $stmt->execute();
    }

    public function updateSocialProvider($id, $provider, $provider_id)
    {
        $query = "UPDATE {$this->table_name}
                  SET provider = :provider, provider_id = :provider_id
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $provider = htmlspecialchars(strip_tags(trim($provider)));
        $provider_id = htmlspecialchars(strip_tags(trim($provider_id)));

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':provider', $provider);
        $stmt->bindParam(':provider_id', $provider_id);

        return $stmt->execute();
    }

    public function createUniqueUsername($base)
    {
        $base = preg_replace('/[^a-zA-Z0-9_]/', '', $base);

        if ($base === '') {
            $base = 'user';
        }

        $username = $base;
        $count = 1;

        while ($this->getAccountByUsername($username)) {
            $username = $base . '_' . $count;
            $count++;
        }

        return $username;
    }
}