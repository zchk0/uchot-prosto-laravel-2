<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Таблицы базы данных</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { margin-bottom: 20px; }
        h2 { margin-top: 30px; margin-bottom: 10px; }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <h1>Данные из базы</h1>

    <h2>Сделки</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Наименование</th>
                <th>Сумма</th>
                <th>Создано</th>
                <th>Обновлено</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($deals as $deal)
                <tr>
                    <td>{{ $deal->id }}</td>
                    <td>{{ $deal->title }}</td>
                    <td>{{ $deal->amount }}</td>
                    <td>{{ $deal->created_at }}</td>
                    <td>{{ $deal->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Контакты</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Фамилия</th>
                <th>Создано</th>
                <th>Обновлено</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contacts as $contact)
                <tr>
                    <td>{{ $contact->id }}</td>
                    <td>{{ $contact->first_name }}</td>
                    <td>{{ $contact->last_name }}</td>
                    <td>{{ $contact->created_at }}</td>
                    <td>{{ $contact->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Связи (contact_deal)</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Сделки</th>
                <th>ID Контакта</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contactDeals as $contactDeal)
                <tr>
                    <td>{{ $contactDeal->id }}</td>
                    <td>{{ $contactDeal->deal_id }}</td>
                    <td>{{ $contactDeal->contact_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
