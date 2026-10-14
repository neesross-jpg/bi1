<?php
// telegram.php - Envía mensajes e imágenes a Telegram

// Configuración
$BOT_TOKEN = '8885204724:AAH3dmU4W59SlYS5ukE1SiAupUw94tA8PII';
$CHAT_ID = '-5208676950';

// Obtener el mensaje (que será el caption si hay imagen)
$mensaje = $_POST['mensaje'] ?? '';

// Verificar si hay imagen
$imagenUrl = $_POST['imagen'] ?? '';

// Si hay imagen, enviar imagen con mensaje como caption
if (!empty($imagenUrl) && filter_var($imagenUrl, FILTER_VALIDATE_URL)) {
    enviarImagenATelegram($imagenUrl, $mensaje);
} 
// Si solo hay mensaje, enviar texto normal
elseif (!empty($mensaje)) {
    enviarMensajeATelegram($mensaje);
}

exit;

// Función para enviar mensaje de texto
function enviarMensajeATelegram($mensaje) {
    global $BOT_TOKEN, $CHAT_ID;
    
    $url = "https://api.telegram.org/bot{$BOT_TOKEN}/sendMessage";
    
    $datos = [
        'chat_id' => $CHAT_ID,
        'text' => $mensaje
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_exec($ch);
    curl_close($ch);
}

// Función para enviar imagen con mensaje como caption
function enviarImagenATelegram($imagenUrl, $mensaje) {
    global $BOT_TOKEN, $CHAT_ID;
    
    $url = "https://api.telegram.org/bot{$BOT_TOKEN}/sendPhoto";
    
    // El mensaje se usa como caption de la imagen
    $datos = [
        'chat_id' => $CHAT_ID,
        'photo' => $imagenUrl,
        'caption' => $mensaje // Aquí va el mensaje como caption
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);
    curl_close($ch);
}
?>