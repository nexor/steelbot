<?php

namespace Steelbot\Protocol\Telegram\Entity;
use Steelbot\ClientInterface;

/**
 * Telegram Chat entity
 */
class Chat implements ClientInterface
{
    const TYPE_PRIVATE = 'private';
    const TYPE_GROUP = 'group';
    const TYPE_SUPERGROUP = 'supergroup';
    const TYPE_CHANNEL = 'channel';

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string|null
     */
    public $title;

    /**
     * @var string|null
     */
    public $username;

    /**
     * @var string|null
     */
    public $firstName;

    /**
     * @var string|null
     */
    public $lastName;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->type  = $data['type'];
        $this->title = $data['title'] ?? null;
        $this->username = $data['username'] ?? null;
        $this->firstName = $data['first_name'] ?? null;
        $this->lastName = $data['last_name'] ?? null;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->id;
    }
}
