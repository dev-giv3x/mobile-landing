<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use MoonShine\Laravel\Notifications\MoonShineNotification;
use MoonShine\Support\Enums\Color;
use MoonShine\Laravel\Models\MoonshineUser;


class LeadController extends Controller
{
    function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'regex:/^[A-Za-zА-Яа-яЁё\-\s]+$/u'],
            'phone' => ['required', 'regex:/^(\+7|8)?\s?\(?\d{3}\)?\s?\d{3}-?\d{2}-?\d{2}$/'],
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/']
        ],
            [
                'name.required' => 'Укажите ваше имя',
                'name.regex' => 'Имя должно состоять только из букв и тире при необходимости',
                'phone.required' => 'Укажите ваш телефон',
                'phone.regex' => 'Телефон должен состоять от 10 до 12 цифр',
                'email.required' => 'Укажите адрес электронной почты',
                'email.regex' => 'Указана недействительная почта',
            ]);
        try {
            $lead = Lead::create($data);

            $managerIds = MoonshineUser::query()
                ->join('moonshine_user_roles', 'moonshine_users.moonshine_user_role_id', '=', 'moonshine_user_roles.id')
                ->where('moonshine_user_roles.name', 'Manager')
                ->pluck('moonshine_users.id')
                ->toArray();

            if (!empty($managerIds)) {
                $fullMessage = "🚀 Новая заявка!\n"
                    . "👤 Имя: {$lead->name}\n"
                    . "📞 Тел: {$lead->phone}\n"
                    . "📧 Email: {$lead->email}";

                MoonShineNotification::send(
                    message: $fullMessage,
                    ids: $managerIds,
                    color: Color::GREEN,
                    icon: 'information-circle'

                );
            }
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
        } catch (\Throwable $e) {
            Log::error("CRITICAL ERROR: " . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'status' => 'error',
                'error_debug' => $e->getMessage()
            ], 500);
        }
    }
    }
