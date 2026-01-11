<?php
require_once __DIR__ . '/env.php';

function notifySubscribers($article)
{
    global $supabaseUrl, $supabaseKey;

    // 1. Fetch all subscribers
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $supabaseKey,
        'Authorization: Bearer ' . $supabaseKey
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $supabaseUrl . '/rest/v1/newsletter_subscriptions?select=email');

    $res = curl_exec($ch);
    $subscribers = json_decode($res, true);
    curl_close($ch);

    if (empty($subscribers) || !is_array($subscribers)) {
        return "Nenhum inscrito para notificar.";
    }

    // 2. Prepare Email Content
    $baseUrl = "https://stoneedger.vercel.app"; // Update with actual domain
    $articleUrl = $baseUrl . "/artigo.php?id=" . $article['id'];
    $subject = "Novo Artigo: " . $article['titulo'];

    $htmlContent = "
        <div style='font-family: sans-serif; max-width: 600px; margin: auto; border: 1px solid #eee; padding: 20px;'>
            <h2 style='color: #D4AF37;'>Stone Edger Insights</h2>
            <img src='{$article['imagem_url']}' style='width: 100%; border-radius: 8px;' />
            <h3>{$article['titulo']}</h3>
            <p>{$article['descricao']}</p>
            <a href='{$articleUrl}' style='display: inline-block; background: #D4AF37; color: #000; padding: 10px 20px; text-decoration: none; font-weight: bold; border-radius: 5px;'>Ler Artigo Completo</a>
            <hr style='margin-top: 30px;' />
            <p style='font-size: 12px; color: #666;'>Você recebeu este e-mail porque está inscrito na newsletter da Stone Edger.</p>
        </div>
    ";

    // 3. Send via Resend (Example)
    $resendKey = get_env_var('RESEND_API_KEY');
    if (!$resendKey) {
        return "Simulado: Notificação preparada para " . count($subscribers) . " pessoas. (Configure RESEND_API_KEY para enviar de verdade)";
    }

    $emails = array_column($subscribers, 'email');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.resend.com/emails');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $resendKey,
        'Content-Type: application/json'
    ]);

    // Resend allows batching or single sends. For a simple implementation:
    $payload = [
        'from' => 'Stone Edger <newsletter@resend.dev>', // Update with verified domain
        'to' => $emails,
        'subject' => $subject,
        'html' => $htmlContent
    ];

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}
?>