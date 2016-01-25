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
     * @var ChosenInlineResult
     */
    public $chosenInlineResult;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->updateId = $data['update_id'];
        $this->message = isset($data['message']) ?
            new Message($data['message']) : null;
        $this->inlineQuery = isset($data['inline_query']) ?
            new InlineQuery($data['inline_query']) : null;
        $this->chosenInlineResult = isset($data['chosen_inline_result']) ?
            new ChosenInlineResult($data['chosen_inline_result']) : null;
    }
}
