<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DealController extends Controller
{
    // Создание новой сделки
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'  => 'required|string|max:255',
            'amount' => 'nullable|numeric',
        ]);

        $deal = Deal::create($validated);

        return response()->json($deal, 201);
    }

    // Получение конкретной сделки (с привязанными контактами)
    public function show($id)
    {
        $deal = Deal::with('contacts')->findOrFail($id);
        return response()->json($deal);
    }

    // Обновление сделки
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'  => 'required|string|max:255',
            'amount' => 'nullable|numeric',
        ]);

        $deal = Deal::findOrFail($id);
        $deal->update($validated);

        return response()->json($deal);
    }

    // Удаление сделки
    public function destroy($id)
    {
        $deal = Deal::findOrFail($id);
        $deal->delete();

        return response()->json(null, 204);
    }
}
