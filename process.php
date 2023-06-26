<?php 
    $from_email         = 'sender@abc.com'; //from mail, sender email address
    $recipient_email = 'tanaka@tsart.jp'; //recipient email address

    //Load POST data from HTML form
    $sender_name = $_POST["sender_name"]; //sender name
    $reply_to_email = $_POST["sender_email"]; //sender email, it will be used in "reply-to" header
    $subject     = "お問い合わせがあります。"; //subject for the email
    $subject = '=?utf-8?B?' . base64_encode($subject) . '?=';
    $gender     = $_POST["gender"]; //subject for the email
    $msg     = $_POST["message"]; //subject for the email
    $pdf     = $_POST["pdf"]; //subject for the email

    $encoded_content = chunk_split($pdf);
    $boundary = md5("random"); // define boundary with a md5 hashed value

    //header
    $headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version
    $headers .= "From:".$from_email."\r\n"; // Sender Email
    $headers .= "Reply-To: ".$reply_to_email."\r\n"; // Email address to reach back
    $headers .= "Content-Type: multipart/mixed;"; // Defining Content-Type
    $headers .= "boundary = $boundary\r\n"; //Defining the Boundary

    //plain text
    $message .= "name: {$sender_name} \r\n";
    $message .= "gender: {$gender} \r\n";
    $message .= "message: ".str_replace("<br />","\r",nl2br($msg))."\r\n";
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=utf-8\r\n";
    // $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= chunk_split(base64_encode($message));

    //attachment
    $body .= "--$boundary\r\n";
    $body .="Content-Type: application/pdf; \r\n";
    $body .="Content-Disposition: attachment; filename=test.pdf\r\n";
    $body .="Content-Transfer-Encoding: base64\r\n";
    $body .="X-Attachment-Id: ".rand(1000, 99999)."\r\n\r\n";
    $body .= $encoded_content; // Attaching the encoded file with email

    $sentMailResult = mail($recipient_email, $subject, $body, $headers);

    if($sentMailResult ){
        echo "<h3>File Sent Successfully.<h3>";
        // unlink($name); // delete the file after attachment sent.
    }
    else{
        die("Sorry but the email could not be sent.
        Please go back and try again!");
    }
?>