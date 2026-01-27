<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:25',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
        ]);

        try {
            $lead = Lead::create($data);

            $token = config('services.telegram.token');
            $chatId = config('services.telegram.chat_id');

            $message = "🚀 **Новая заявка!**\n\n"
                . "👤 Имя: {$lead->name}\n"
                . "📞 Тел: {$lead->phone}\n"
                . "📧 Email: " . ($lead->email ?? 'не указан');

            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);

            return response()->json(['status' => 'success', 'message' => 'Лид сохранен и отправлен'], 201);
        } catch (\Exception $e) {
            Log::error("Ошибка при обработке лида: " . $e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Что-то сломалось'], 500);

        }
    }
}
