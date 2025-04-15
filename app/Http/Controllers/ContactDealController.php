<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Contact;
use App\Models\ContactDeal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactDealController extends Controller
{
    public function index()
    {
        // Выборка данных
        $deals = Deal::with('contacts')->get();
        $contacts = Contact::with('deals')->get();

        // Передаём данные в шаблон
        return view('contactDeal', compact('deals', 'contacts'));
    }

    // Привязка контакта к сделке
    public function store(Request $request)
    {
        $validated = $request->validate([
            'deal_id'    => 'required|exists:deals,id',
            'contact_id' => 'required|exists:contacts,id',
        ]);

        $deal = Deal::findOrFail($validated['deal_id']);
        $deal->contacts()->syncWithoutDetaching([$validated['contact_id']]);

        return response()->json([
            'message' => 'Контакт успешно привязан к сделке'
        ], 200);
    }

    public function destroy(string $id)
    {
        $сontactDeal = ContactDeal::where('id', $id)->first();
        $сontactDeal->delete();

        return response()->json([
            'message' => 'Связь успешно удалена'
        ], 200);
    }
}
