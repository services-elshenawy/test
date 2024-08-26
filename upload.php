<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $image = $_FILES['image']['tmp_name'];
    $imageName = $_FILES['image']['name'];

    // تحقق من صحة رقم الهاتف
    if (empty($phone) || !preg_match('/^[0-9]{10,15}$/', $phone)) {
        echo "رقم الهاتف غير صحيح.";
        exit;
    }

    // تحقق من صحة الصورة
    if (empty($image)) {
        echo "يجب إرفاق صورة.";
        exit;
    }

    // إعداد البريد الإلكتروني
    $to = "hos2422394@gmail.com";
    $subject = "صورة ورقم هاتف من الموقع";
    $message = "رقم الهاتف: " . $phone;
    $headers = "From: your-email@example.com";

    // تحميل محتوى الصورة
    $fileContent = file_get_contents($image);
    $encodedImage = chunk_split(base64_encode($fileContent));

    // إعداد المرفق
    $boundary = md5(time());
    $headers .= "\r\nMIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"".$boundary."\"";
    $messageBody = "--".$boundary."\r\n";
    $messageBody .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
    $messageBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $messageBody .= $message."\r\n";
    $messageBody .= "--".$boundary."\r\n";
    $messageBody .= "Content-Type: application/octet-stream; name=\"".$imageName."\"\r\n";
    $messageBody .= "Content-Transfer-Encoding: base64\r\n";
    $messageBody .= "Content-Disposition: attachment; filename=\"".$imageName."\"\r\n\r\n";
    $messageBody .= $encodedImage."\r\n";
    $messageBody .= "--".$boundary."--";

    // إرسال البريد الإلكتروني
    if (mail($to, $subject, $messageBody, $headers)) {
        echo "تم إرسال الصورة ورقم الهاتف بنجاح.";
    } else {
        echo "حدث خطأ أثناء الإرسال.";
    }
}
?>

