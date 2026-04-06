<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Form verilerini al ve temizle
    $isyeri_adi = htmlspecialchars(strip_tags(trim($_POST['isyeri_adi'])));
    $adres = htmlspecialchars(strip_tags(trim($_POST['adres'])));
    $sicil_no = htmlspecialchars(strip_tags(trim($_POST['sicil_no'] ?? '')));
    $calisan_sayisi = htmlspecialchars(strip_tags(trim($_POST['calisan_sayisi'] ?? '')));
    $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
    $mesaj = htmlspecialchars(strip_tags(trim($_POST['mesaj'])));
    
    // Basit doğrulama
    if (empty($isyeri_adi) || empty($adres) || empty($email) || empty($mesaj)) {
        header("Location: iletisim.html?error=1");
        exit;
    }
    
    // E-posta doğrulama
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: iletisim.html?error=1");
        exit;
    }
    
    // Alıcı e-posta adresi
    $to = "info@kalicertisg.com";
    
    // E-posta konusu
    $subject = "Kalicert İSG İletişim Formu - " . $isyeri_adi;
    
    // E-posta içeriği
    $message = "
    <html>
    <head>
        <title>Kalicert İSG İletişim Formu</title>
    </head>
    <body>
        <h2>Kalicert İSG Web Sitesinden Yeni Mesaj</h2>
        <table border='0' cellpadding='5'>
            <tr><td><strong>İşyeri Adı:</strong></td><td>$isyeri_adi</td></tr>
            <tr><td><strong>Adres:</strong></td><td>$adres</td></tr>
            <tr><td><strong>İşyeri Sicil No:</strong></td><td>" . ($sicil_no ?: 'Belirtilmemiş') . "</td></tr>
            <tr><td><strong>Çalışan Sayısı:</strong></td><td>" . ($calisan_sayisi ?: 'Belirtilmemiş') . "</td></tr>
            <tr><td><strong>Gönderen E-posta:</strong></td><td>$email</td></tr>
            <tr><td><strong>Mesaj:</strong></td><td>$mesaj</td></tr>
        </table>
        <hr>
        <small>Bu mesaj Kalicert İSG web sitesi iletişim formundan gönderilmiştir.</small>
    </body>
    </html>
    ";
    
    // Header bilgileri (HTML mail için)
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Mail gönder
    if (mail($to, $subject, $message, $headers)) {
        header("Location: iletisim.html?success=1");
    } else {
        header("Location: iletisim.html?error=1");
    }
    exit;
} else {
    // Form dışı erişim
    header("Location: iletisim.html");
    exit;
}
?>