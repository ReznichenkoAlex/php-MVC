<?php

namespace App\Model;

use Base\AbstractModel;

class Message extends AbstractModel
{
    private int $id;
    private string $text;
    private string $date;
    private int $userId;
    private string $userName;
    private string $image;

    /**
     * @return string
     */
    public function getImage(): ?string
    {
        if (empty($this->image)) {
            return null;
        }
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;
        return $this;
    }

    public static function getMessages(): array
    {
        $db = new \PDO("mysql:host=127.0.0.1;dbname=mvc", 'root', '');
        $query = "SELECT * FROM posts ORDER BY id DESC LIMIT 20";
        $ret = $db->query($query);
        $messages = $ret->fetchAll(\PDO::FETCH_ASSOC);

        return $messages;
    }

    public static function getMessagesByUserId($id): array
    {
        $db = new \PDO("mysql:host=127.0.0.1;dbname=mvc", 'root', '');
        $query = "SELECT * FROM posts WHERE user_id = :id ORDER BY id DESC LIMIT 20";
        $prepared = $db->prepare($query);
        $prepared->execute(['id' => $id]);
        $messages = $prepared->fetchAll(\PDO::FETCH_ASSOC);

        return $messages;
    }

    public function addMessage()
    {
        $db = new \PDO("mysql:host=127.0.0.1;dbname=mvc", 'root', '');
        $query = "
                INSERT
                    INTO
                        posts (
                               text,
                               user_id,
                               user_name,
                               image
                              ) 
                              VALUES 
                             (
                              :message,
                              :user_id,
                              :user_name,
                              :image
                              );";
        $prepared = $db->prepare($query);
        $ret = $prepared->execute([
            'message' => $this->getText(),
            'user_id' => $this->getUserId(),
            'user_name' => $this->getUserName(),
            'image' => $this->getImage()
        ]);
    }

    public static function deleteMessageByPostId($post_id)
    {
        $db = new \PDO("mysql:host=127.0.0.1;dbname=mvc", 'root', '');
        $query = "DELETE FROM posts WHERE id = :id";
        $prepared = $db->prepare($query);
        $prepared->execute(['id' => $post_id]);
    }


}