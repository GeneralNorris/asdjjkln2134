<?php
// bool mail ( string $to , string $subject , string $message [, string $additional_headers [, string $additional_parameters ]] )
$to = "martin@hofheinz.de";
$subject = "Test E-Mail";
$message = "Dies ist eine Test E-Mail";
$success = mail ($to, $subject, $message, "From:info@engelbaum.de\n");
echo $success;
