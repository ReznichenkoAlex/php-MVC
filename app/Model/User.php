<?php

namespace App\Model;

use Base\AbstractModel;
use Base\Db;

class User extends AbstractModel
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $createdAt;

    public function __construct($data = [])
    {
        if ($data) {
            $this->id = $data['id'];
            $this->name = $data['name'];
            $this->email = $data['email'];
            $this->password = $data['password'];
            $this->createdAt = $data['created_at'];
        }
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function save(): void
    {
        $db =  new \PDO("mysql:host=127.0.0.1;dbname=mvc", 'root', '');
        $query = 'INSERT INTO users (`name`, `email`, `password`) VALUES (:name, :email, :password)';
        $prepared = $db->prepare($query);
        $prepared->execute([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password
        ]);

        $id = $db->lastInsertId();
        $this->setId($id);
    }

    public static function getPasswordHash(string $password): string
    {
        return sha1('sadqwrwq' . $password);
    }

    public static function getById(int $id): ?self
    {
        $db =  new \PDO("mysql:host=127.0.0.1;dbname=mvc", 'root', '');
        $query = "SELECT * FROM users WHERE id = $id";
        $ret = $db->query($query);
        $data = $ret->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new self($data);
    }

    public static function getByEmail(string $email): ?self
    {
        $db =  new \PDO("mysql:host=127.0.0.1;dbname=mvc", 'root', '');
        $query = "SELECT * FROM users WHERE `email` = :email";
        $prepared = $db->prepare($query);
        $prepared->execute(['email' => $email]);
        $data = $prepared->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new self($data);
    }


}