<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    public function webhook(Request $request)
    {
        $update = Telegram::commandsHandler(true);

        // Xử lý các lệnh ở đây
        if ($update->isCommand('lehongthai')) {
            $chat_id = $update->getMessage()->getChat()->getId();
            Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Xin chào! Đây là bot của bạn.'
            ]);
        }

        return 'ok';
    }
}
