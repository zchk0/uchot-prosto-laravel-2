<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Deal;

class ContactDealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаём контакты
        $contact1 = Contact::create([
            'first_name' => 'Иван',
            'last_name'  => 'Петров',
        ]);
        $contact2 = Contact::create([
            'first_name' => 'Наталья',
            'last_name'  => 'Сидорова',
        ]);
        $contact3 = Contact::create([
            'first_name' => 'Василий',
            'last_name'  => 'Иванов',
        ]);

        // Создаём сделки
        $deal1 = Deal::create([
            'title'  => 'Хотят люстру',
            'amount' => 4000,
        ]);
        $deal2 = Deal::create([
            'title'  => 'Пока думают',
            'amount' => 25,
        ]);
        $deal3 = Deal::create([
            'title'  => 'Хотят светильник',
            'amount' => 15,
        ]);

        // Связь (attach) в pivot-таблицу contact_deal
        $deal1->contacts()->attach([$contact1->id, $contact2->id]);
        $deal2->contacts()->attach([$contact3->id]);
        $deal3->contacts()->attach([$contact1->id, $contact2->id, $contact3->id]);
    }
}
