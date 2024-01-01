<?php

namespace Modules\Notification\Http\Notifications\Channels\Notice\Layouts\Telegram;


use App\Http\Controllers\Controller;
use Modules\Notification\Http\Notifications\Channels\Notice\Layouts\Telegram\Layouts\Telegram\Telegram;
use Modules\Notification\Http\Notifications\Channels\Notice\Layouts\Telegram\Layouts\Traits\FunctionGetUpdateType;

class TelegramController extends Controller
{
    //public $user=['username'=>null,'last_name'=>null,'first_name'=>null,'chat_id'=>null,'switch'=>null];
    public $telegram = null;
    public $request = null;
    public $text = null;
    public $chat_id = null;
    public $messageType = null;
    public $menu = null;
    use FunctionGetUpdateType;

    public function __construct()
    {
        //set api token telegram
        $this->telegram = new Telegram(env('TELEGRAM_TOKEN'), false);

        //fetch information
        $this->fetchInformation();
    }

    public function forceReplyMessage($text, $chat_id = null)
    {
        if (!$chat_id)
            $chat_id = $this->getchat_id();
        $content = array('chat_id' => $chat_id, 'text' => $text, 'reply_markup' => $this->telegram->buildForceReply());
        $this->telegram->sendMessage($content);
    }

    public function replyToMessage($text, $chat_id = null)
    {
        if (!$chat_id)
            $chat_id = $this->getchat_id();
        $content = array(
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => $this->menu,
            'reply_to_message_id' => $this->request["message"]["message_id"]);
        $this->telegram->sendMessage($content);
    }

    /**
     * @param $text
     * @param null $chat_id
     */
    public function responseMessage($text, $chat_id = null)
    {
        if (!$chat_id)
            $chat_id = $this->getchat_id();
        $content = array('chat_id' => $chat_id, 'text' => $text, 'reply_markup' => $this->menu);
        $this->telegram->sendMessage($content);
    }


    public function getTextMessage()
    {
        return isset($this->request["message"]["text"]) ? $this->request["message"]["text"] : null;
    }

    public function getchat_id()
    {
        return $this->telegram->chat_id();
    }

    public function omen()
    {

    }
}
