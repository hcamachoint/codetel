<?php namespace App\Controllers;

use Telegram\TuriBot\Client;

class Home extends BaseController
{
	public function index()
	{
		return view('welcome_message');
	}

	public function test()
	{
				$client = new Client(getEnv('bot.key'));
				$offset = 0;

				while (true) {
			    $updates = $client->getUpdates($offset, $timeout = 0);
			    if ($updates->ok == true) {
			        foreach ($updates->result as $update) {
			            $offset = $update->update_id + 1;
			            $easy = new \Telegram\TuriBot\EasyVars($update);

			            if (isset($update->message)) {
			                $chat_id = $update->message->chat->id;
			                if (isset($update->message->reply_to_message->from->id)) {
			                    $reply_id = $update->message->reply_to_message->from->id;
			                }

			                if ($easy->text === "ping") {
			                    $client->sendMessage($chat_id, "pong");
			                } else {
			                    $client->sendMessage($chat_id, $easy->message_id);
			                }

			                if ($easy->text === "test") {
			                    $result = $client->sendMessage($chat_id, "test");
			                    $client->debug($chat_id, $result);
			                }
			                //$client->sendPhoto($chat_id, $client->inputFile("photo.png"));

			                if ($easy->text === "/mute" and isset($reply_id)) {
			                    $perm = [
			                        "can_send_messages" => false
			                    ];
			                    $result = $client->restrictChatMember($chat_id, $reply_id, $perm);
			                    $client->debug($chat_id, $result);
			                }
			            } elseif (isset($update->inline_query)) {
			                $out = (string)rand();
			                $results[] = [
			                    "type"                  => "article",
			                    "id"                    => $out,
			                    "title"                 => $out,
			                    "input_message_content" => [
			                        "message_text"             => $out,
			                        "disable_web_page_preview" => true
			                    ],
			                ];
			                $client->answerInlineQuery($update->inline_query->id, $results, 1, false);
			                unset($results);
			            }

			        }
			    } else {
			        exit($updates->description);
			    }
			}
	}

	//--------------------------------------------------------------------

}
