<?php
// ============================================================
// GENERAR CLAVES - MÉTODO ALTERNATIVO
// ============================================================

// Función para generar claves RSA
function generarClavesRSA($bits = 2048) {
    // Crear el par de claves
    $privateKey = openssl_pkey_new([
        'private_key_bits' => $bits,
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ]);

    if (!$privateKey) {
        return false;
    }

    // Exportar clave privada
    openssl_pkey_export($privateKey, $privKey);

    // Obtener clave pública
    $pubKey = openssl_pkey_get_details($privateKey);
    $pubKey = $pubKey['key'];

    return [
        'private' => $privKey,
        'public' => $pubKey
    ];
}

// Generar claves
echo " Generando claves RSA de 2048 bits...\n\n";

$keys = generarClavesRSA(2048);

if (!$keys) {
    die(" Error: " . openssl_error_string());
}

// Guardar archivos
file_put_contents(__DIR__ . '/private_key.pem', $keys['private']);
file_put_contents(__DIR__ . '/public_key.pem', $keys['public']);

echo " Clave privada: private_key.pem\n";
echo " Clave pública: public_key.pem\n";
echo "\n ¡Listo!\n";
?>