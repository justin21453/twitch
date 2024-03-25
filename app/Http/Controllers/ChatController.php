<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;

class ChatController extends Controller
{
    public function show()
    {

        // $process = new Process(['php artisan app:get-twitch-chat']);
        // $process->run(function ($type, $buffer): void {
        //     if (Process::ERR === $type) {
        //         echo 'ERR > '.$buffer;
        //     } else {
        //         echo 'OUT > '.$buffer;
        //     }
        // });

        // Artisan::call('app:get-twitch-chat');

        // Process::run()
        // $result = Process::path(__DIR__)->run('app:get-twitch-chat');
        // echo $result->output();
        // echo "success";
        // echo $result->output();
        // echo "error";
        // echo $result->errorOutput();

        return view('chat');
    }
}
