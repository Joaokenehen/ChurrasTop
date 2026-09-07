<?php
declare(strict_types=1);

function formatCurrency(float $value): string
{
    return 'R$ ' . number_format($value, 2, ',', '.');
}

function filterProducts(array $products, string $search): array
{
    $search = trim(mb_strtolower($search));
    if ($search === '') {
        return $products;
    }

    return array_values(array_filter($products, static function (array $product) use ($search): bool {
        return str_contains(mb_strtolower($product['name']), $search)
            || str_contains(mb_strtolower($product['category']), $search);
    }));
}

function validateProducts(array $products): array
{
    $errors = [];
    if ($products === []) {
        $errors[] = 'Nenhum produto foi encontrado.';
    }
    foreach ($products as $product) {
        if ((float) $product['price'] < 0 || (int) $product['stock'] < 0) {
            $errors[] = 'Existem produtos com preço ou estoque inválido.';
            break;
        }
    }
    return $errors;
}

function eventSummary(array $events): array
{
    $summary = ['total' => count($events), 'budget' => 0.0];
    foreach ($events as $event) {
        $summary['budget'] += (float) $event['budget'];
    }
    return $summary;
}

function validateEvent(array $data): array
{
    $errors = [];
    if (trim((string) ($data['title'] ?? '')) === '') {
        $errors[] = 'Informe o nome do evento.';
    }
    if (trim((string) ($data['event_date'] ?? '')) === '') {
        $errors[] = 'Informe a data do evento.';
    }
    if (trim((string) ($data['location'] ?? '')) === '') {
        $errors[] = 'Informe o local do evento.';
    }
    if (!is_numeric($data['budget'] ?? null) || (float) $data['budget'] < 0) {
        $errors[] = 'Informe um orçamento válido.';
    }
    return $errors;
}

function syncEventParticipants(PDO $db, int $eventId, array $participants): void
{
    $delete = $db->prepare('DELETE FROM event_participants WHERE event_id = ?');
    $delete->execute([$eventId]);
    $findUser = $db->prepare('SELECT id FROM users WHERE phone = ? LIMIT 1');
    $createUser = $db->prepare('INSERT INTO users (name, email, phone) VALUES (?, NULL, ?)');
    $insert = $db->prepare('INSERT INTO event_participants (event_id, user_id, confirmed) VALUES (?, ?, 0)');
    foreach ($participants as $participant) {
        $name = trim((string) ($participant['name'] ?? ''));
        $phone = trim((string) ($participant['phone'] ?? ''));
        if ($name === '' || $phone === '') {
            continue;
        }

        $findUser->execute([$phone]);
        $userId = $findUser->fetchColumn();
        if (!$userId) {
            $createUser->execute([$name, $phone]);
            $userId = $db->lastInsertId();
        } else {
            $updateUser = $db->prepare('UPDATE users SET name = ? WHERE id = ?');
            $updateUser->execute([$name, $userId]);
        }
        $insert->execute([$eventId, (int) $userId]);
    }
}

function findOrCreateUser(PDO $db, string $name, string $phone): int
{
    $find = $db->prepare('SELECT id FROM users WHERE phone = ? LIMIT 1');
    $find->execute([$phone]);
    $userId = $find->fetchColumn();
    if ($userId) {
        $update = $db->prepare('UPDATE users SET name = ? WHERE id = ?');
        $update->execute([$name, $userId]);
        return (int) $userId;
    }

    $create = $db->prepare('INSERT INTO users (name, email, phone) VALUES (?, NULL, ?)');
    $create->execute([$name, $phone]);
    return (int) $db->lastInsertId();
}
