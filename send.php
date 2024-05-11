<?php
$Fname = $_POST['Fname'];
$Lname = $_POST['Lname'];
$visitor_email = $_POST['email'];
$visitor_phone = $_POST['number'];
$cin = $_POST['CIN'];
$city = $_POST['City'];
$message = $_POST['message'];
$file = $_FILES['file'];

$email_from = 'ayman@localhost';
$email_subject = "New Form Submission";

$to = 'marocfiree@gmail.com';

$headers = "From: $email_from\r\n";
$headers .= "Reply-To: $visitor_email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"mixed_boundary\"\r\n";
/*MIME-Version: 1.0 : Cette ligne spécifie la version de MIME 
(Multipurpose Internet Mail Extensions) utilisée dans l'e-mail. 
MIME est un standard qui permet d'inclure des contenus non textuels, 
tels que des images, des fichiers audio et des pièces jointes, 
dans les e-mails. La version 1.0 est la version actuelle de MIME utilisée pour la plupart des e-mails.*/
$boundary = "mixed_boundary";


$email_message = "--$boundary\r\n";
$email_message .= "Content-Type: text/Html; charset=\"UTF-8\"\r\n";
$email_message .= "Content-Transfer-Encoding: 7bit\r\n";
$email_message .= "\n";
/*$email_message .= "Content-Type: text/Html; charset="UTF-8"\r\n"; : 
Cette ligne spécifie le type de contenu de la première partie de l'e-mail.
Dans cet exemple, il s'agit de texte HTML, et le jeu de caractères est défini comme UTF-8 
pour prendre en charge les caractères internationaux.
$email_message .= "Content-Transfer-Encoding: 7bit\r\n"; : Cette ligne spécifie le codage
utilisé pour transférer le contenu de l'e-mail. Dans cet exemple, le codage 7bit est utilisé, 
ce qui est approprié pour les contenus en texte brut ou en texte HTML sans pièces jointes binaires.*/
$email_message .= "<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>Submitted Data</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f7f7;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 700px;
        margin: 50px auto;
        background-color: #fff;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        text-align: center; /* Center align the content */
    }
    h1 {
        font-size: 36px;
        color: #003580;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 30px;
        text-align: center;
    }
    .title {
        background-color: #003580;
        color: #fff;
        padding: 10px 20px;
        border-radius: 10px;
        display: inline-block;
        font-size: 24px;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        margin: 0 auto 40px; /* Center the title */
    }
    .data label {
        font-weight: bold;
        color: #003580;
        display: block;
        margin-bottom: 5px;
    }
    .data p {
        margin: 0;
        color: #444;
    }
    .divider {
        width: 100%;
        height: 2px;
        background-color: #003580;
        margin: 40px 0;
    }
    .footer {
        color: #666;
        font-size: 14px;
        text-align: center;
    }
</style>
</head>
<body>

<div class='container'>
    <div class='title'>CASH PLUS</div>
    <h1>Registration Details</h1>
    <div class='data' style='text-align: left;'>
        <label for='name'>Name:</label>
        <p id='name'>$Fname $Lname</p>
        
        <label for='email'>Email:</label>
        <p id='email'>$visitor_email</p>

        <label for='Phone Number'>Phone Number:</label>
        <p id='Phone Number'>$visitor_phone</p>

        <label for='City'>City:</label>
        <p id='City'>$cin</p>

        <label for='CIN'>CIN:</label>
        <p id='CIN'>$city</p>
        
        <label for='message'>Message:</label>
        <p id='message'>$message</p>
    </div>
    <div class='divider'></div>
    <p class='footer'>Thank you for registering with us. &copy; 2024 Cash Plus. All rights reserved.</p>
</div>

</body>
</html>";
$email_message .= "\n";


if ($file['error'] == 0) {
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_type = $file['type'];

    if ($file_type == "application/pdf") {
        $email_message .= "--$boundary\r\n";
        $email_message .= "Content-Type: application/pdf; name=\"$file_name\"\r\n";
        $email_message .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
        $email_message .= "Content-Transfer-Encoding: base64\r\n";
        $email_message .= "\n";
        $file_content = chunk_split(base64_encode(file_get_contents($file_tmp)));
        /*En résumé, cette ligne de code prend le contenu d'un fichier, 
        l'encode en base64, le divise en morceaux de taille spécifiée, 
        et renvoie la chaîne encodée en base64 prête à être insérée comme pièce jointe dans l'e-mail.
        smtp:Simple Mail Transfer Protocol
        boundary" se traduit par "limite" ou "frontière". Dans le contexte du protocole MIME (Multipurpose Internet Mail Extensions), 
        il est utilisé pour délimiter les différentes parties d'un message*/
        $email_message .= $file_content;
    } else {
        header("location: form.html?file=1");
        exit();
    }
} else {
    header("location: form.html?no=1");
    exit();
}

if (mail($to, $email_subject, $email_message, $headers)) {
    header("location: form.html?success=1");
    exit();
} else {
    header("location: form.html?error=1");
}


