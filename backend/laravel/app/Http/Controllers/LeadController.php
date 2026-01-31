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
            'name' => ['required', 'regex:/^[A-Za-zА-Яа-яЁё\-\s]+$/u'],
            'phone' => ['required','regex:/^\d{10,12}$/'],
            'email' => 'required|email',
        ], ['name.required'  => 'Укажите ваше имя',
            'name.regex'     => 'Имя должно состоять только из букв и тире при необходимости',
            'phone.required' => 'Укажите ваш телефон',
            'phone.regex'    => 'Телефон должен состоять от 10 до 12 цифр',
            'email.required' => 'Укажите адрес электронной почты',
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

            return response()->json(['status' => 'error', 'message' => 'Произошла ошибка при обработке запроса'], 500);

        }
    }
}
