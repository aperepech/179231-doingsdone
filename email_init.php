<?php

require_once 'vendor/autoload.php';
require_once 'options.php';

// Create the Transport
$transport = (new Swift_SmtpTransport($email['host'], $email['port']))
    ->setUsername($email['username'])
    ->setPassword($email['password']);

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);
