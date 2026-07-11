<?php
// ============================================================
// GENERAR CLAVES CON PHP (usando la extensión openssl de PHP)
// ============================================================

echo " Generando claves RSA de 2048 bits...\n\n";

// Verificar que la extensión openssl esté activa revision
if (!extension_loaded('openssl')) {
    die(" La extensión OpenSSL NO está activa en PHP.\n");
}

echo " Extensión OpenSSL está activa\n\n";

// Configuración para generar las claves
$config = array(
    "digest_alg" => "sha256",
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
);

// Generar el par de claves
$res = openssl_pkey_new($config);

if (!$res) {
    die(" Error al generar las claves: " . openssl_error_string());
}

echo " Par de claves generado correctamente\n";

// Extraer la clave privada
openssl_pkey_export($res, $privateKey);

// Extraer la clave pública
$publicKey = openssl_pkey_get_details($res);
$publicKey = $publicKey["key"];

// Guardar clave privada
file_put_contents(__DIR__ . '/private_key.pem', $privateKey);
echo " Clave privada guardada en: private_key.pem\n";

// Guardar clave pública
file_put_contents(__DIR__ . '/public_key.pem', $publicKey);
echo " Clave pública guardada en: public_key.pem\n";

// Mostrar información
echo "\n INFORMACIÓN DE LAS CLAVES:\n";
echo "----------------------------------------\n";
echo "Clave privada: " . strlen($privateKey) . " caracteres\n";
echo "Clave pública: " . strlen($publicKey) . " caracteres\n";
echo "----------------------------------------\n";
echo "\n ¡Claves generadas exitosamente!\n";
echo " private_key.pem → Usar para FIRMAR\n";
echo " public_key.pem  → Usar para VERIFICAR\n";

// Mostrar la ruta donde se guardaron
echo "\n Ubicación: " . __DIR__ . "\n";

// Mostrar un preview de las claves
echo "\n Preview de las claves:\n";
echo "----------------------------------------\n";
echo "PRIVATE KEY (primeros 100 caracteres):\n";
echo substr($privateKey, 0, 100) . "...\n\n";
echo "PUBLIC KEY (primeros 100 caracteres):\n";
echo substr($publicKey, 0, 100) . "...\n";
echo "----------------------------------------\n";
?>