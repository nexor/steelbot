<?php
namespace Steelbot\Protocol\Telegram\Entity;

class Update
{
    /**
     * @var integer
     */
    public $updateId;

    /**
     * @var Message
     */
    public $message;

    /**
     * @var InlineQuery
     */
    public $inlineQuery;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->updateId = $data['update_id'];
        $this->message = $data['message'] ?? new Message($data['message']);
        $this->inlineQuery = $data['inline_query'] ?? new InlineQuery($data['inline_query']);
    }
}
