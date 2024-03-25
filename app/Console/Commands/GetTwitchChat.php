<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TwitchChatClient;
use Illuminate\Support\Facades\Log;
use App\Events\ChatSendMessage;

class GetTwitchChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-twitch-chat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $twitch_chat_client = new TwitchChatClient(env("TWITCH_USER"), env("TWITCH_OAUTH"));
        $twitch_chat_client->connect();

        if (!$twitch_chat_client->isConnected()) {
            Log::info("unconnected");
        }
        else{
            Log::info("connected");
        }

        while (true) {
            $content = $twitch_chat_client->read(2048);
            Log::info($content);

            if (!$twitch_chat_client->isConnected()) {
                Log::info("unconnected");
                break;
            }
            if (strstr($content, 'PING')) {
                $twitch_chat_client->send('PONG :tmi.twitch.tv');
                continue;
            }
            else if (strstr($content, 'PRIVMSG')) {
                $this->printMessage($content);
                continue;
            }
        }
    }

    public function printMessage($raw_message)
    {
        $parts = explode(":", $raw_message, 3);
        $nick_parts = explode("!", $parts[1]);

        $nick = $nick_parts[0];
        $message = $parts[2];

        $style_nick = "other";

        if ($nick === env("TWITCH_USER")) {
            $style_nick = "self";
        }

        Log::info($nick . ": " . $message);
        // event(new ChatSendMessage($nick, $message));

        ChatSendMessage::dispatch($nick, $message);
    }
}
