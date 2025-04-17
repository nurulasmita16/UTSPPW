<?php
define('DATA_FILE', 'data_obat.json');

function loadObat() {
    if (file_exists(DATA_FILE)) {
        $json = file_get_contents(DATA_FILE);
        return json_decode($json, true);
    } else {
        // Default data
        return [
            ['id' => 1, 'nama' => 'Paracetamol', 'harga' => 5000, 'stok' => 100],
            ['id' => 2, 'nama' => 'Amoxicillin', 'harga' => 15000, 'stok' => 50],
            ['id' => 3, 'nama' => 'Vitamin C', 'harga' => 20000, 'stok' => 75],
            ['id' => 4, 'nama' => 'Antangin', 'harga' => 10000, 'stok' => 30],
            ['id' => 5, 'nama' => 'Bodrex', 'harga' => 8000, 'stok' => 60],
        ];
    }
}

function saveObat($data) {
    file_put_contents(DATA_FILE, json_encode($data, JSON_PRETTY_PRINT));
}

function getObatById($id) {
    $data_obat = loadObat();
    foreach ($data_obat as $obat) {
        if ($obat['id'] == $id) {
            return $obat;
        }
    }
    return null;
}

function updateStokObat($id, $jumlah) {
    $data_obat = loadObat();
    foreach ($data_obat as &$obat) {
        if ($obat['id'] == $id) {
            $obat['stok'] += $jumlah;
            saveObat($data_obat);
            return true;
        }
    }
    return false;
}
function getAllObat() {
    return loadObat();
}
?>
