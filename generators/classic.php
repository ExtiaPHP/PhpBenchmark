<?php

function storeJsonUser() {
    foreach(getUserData() as $userData) {
        file_put_contents(__DIR__ . '/classic/user-' . $userData['id'] . '.json', json_encode($userData));
        die;
    }
}

function getUserData() {
    $handle = fopen(__DIR__ . '/fixtures/users.csv', 'r');
    rewind($handle);
    
    $userData = [];
    while($row = fgets($handle)) {
        $data = explode(';', $row);
        $userData[] = [
            'id' => (int) $data[0],
            'firstname' => $data[1],
            'lastname' => $data[2],
            'email' => $data[3],
            'is_enabled' => (bool) $data[4],
            'is_banned' => (bool) $data[5],
            'birthdate' => new Datetime($data[6]),
            'created_at' => new DateTime($data[7]),
            'transactions' => getTransactions($data[0]),
            'products' => getProducts($data[0])
        ];
    }
    fclose($handle);
    return $userData;
}

function getTransactions($userId) {
    $handle = fopen(__DIR__ . '/fixtures/transactions.csv', 'r');
    $transactions = [];
    
    while($row = fgets($handle)) {
        $data = explode(';', $row);
        if($data[0] === $userId) {
            $transactions[] = [
                'broker_id' => (int) $data[1],
                'amount' => (float) $data[2],
                'date' => new DateTime($data[3])
            ];
        }
    }
    fclose($handle);
    return $transactions;
}

function getProducts($userId) {
    $handle = fopen(__DIR__ . '/fixtures/products.csv', 'r');
    $products = [];
    
    while($row = fgets($handle)) {
        $data = explode(';', $row);
        if($data[0] === $userId) {
            $products[] = [
                'name' => $data[1],
                'quantity' => (int) $data[2],
                'price' => (float) $data[3]
            ];
        }
    }
    fclose($handle);
    return $products;
}

storeJsonUser();