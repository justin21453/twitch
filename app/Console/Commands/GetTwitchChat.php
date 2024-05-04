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
        $stop_socket = false;
        while(!$stop_socket){
            $twitch_chat_client = new TwitchChatClient(env("TWITCH_USER"), env("TWITCH_OAUTH"));
            $twitch_chat_client->connect();

            if (!$twitch_chat_client->isConnected()) {
                Log::info("unconnected");
                continue;
            }
            else{
                Log::info("connected");
            }

            $twitch_chat_client->send('CAP REQ :twitch.tv/commands twitch.tv/tags');

            while (true) {
                $content = $twitch_chat_client->read(2048);
                Log::info($content);

                if (!$twitch_chat_client->isConnected() || !$content) {
                    Log::info("disconnected");
                    break;
                }
                if (strstr($content, 'PING')) {
                    $twitch_chat_client->send('PONG :tmi.twitch.tv');
                    continue;
                }
                else if (strstr($content, 'PRIVMSG')) {
                    $parts = explode("PRIVMSG", $content, 2);
                    $nick = $this->get_string_between($parts[0], '!', '@');
                    if (strstr($parts[1], '!!!close!!!') && $nick == 'justin21453') {
                        Log::info("I close the socket");
                        $stop_socket = true;
                        break;
                    }


                    $this->printMessage($content);
                    continue;
                }
            }
            $twitch_chat_client->close();
            sleep(10);
        }
    }

    public function printMessage($raw_message)
    {
        $parts = explode("PRIVMSG", $raw_message, 2);
        $nick = $this->get_string_between($parts[0], '!', '@');
        $original_message = explode(":", $parts[1], 2)[1];
        $message = explode(":", $parts[1], 2)[1];
        $user_setting = explode(" :", $raw_message, 2)[0];
        $user_setting_ary = explode(";", $user_setting);
        $setting = [];

        foreach($user_setting_ary as $row){
            $key_value = explode("=", $row, 2);
            $key = $key_value[0];
            $value = $key_value[1];
            if(!$value){
                continue;
            }

            if($key == 'emotes'){
                $emotes_ary = explode("/", $value);

                foreach($emotes_ary as $emote){
                    $emote_set = explode(":", $emote, 2);
                    $emote_id = $emote_set[0];
                    $emote_positions = explode(",", $emote_set[1]);
                    $emote_url = "https://static-cdn.jtvnw.net/emoticons/v2/$emote_id/default/light/2.0";
                    $emote_html = "<img src='$emote_url'></img>";
                    foreach($emote_positions as $emote_position){
                        $emote_position_part = explode("-", $emote_position);
                        $emote_position_start = $emote_position_part[0];
                        $emote_position_end = $emote_position_part[1];
                        $length = $emote_position_end - $emote_position_start + 1;

                        // substr_replace($message, $emote_html, $emote_position_start, $length);
                        $emote_string = mb_substr($original_message, $emote_position_start, $length, 'UTF-8');
                        $message = str_replace($emote_string, $emote_html, $message);
                    }
                }
            }
            else{
                $setting[$key] = $value;
            }
        }

        // $nick = $nick_parts[0];
        // $message = $parts[2];

        $style_nick = "other";

        if ($nick === env("TWITCH_USER")) {
            $style_nick = "self";
        }

        Log::info($nick . ": " . $message);
        // event(new ChatSendMessage($nick, $message));

        ChatSendMessage::dispatch($nick, $message, $setting);
    }

    public function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}
