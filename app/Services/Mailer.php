<?php
// app/Services/Mailer.php

class Mailer
{
    
    private string $mode;

    public function __construct(string $mode = 'log')
    {
        $this->mode = $mode;
    }

    public function send(string $to, string $subject, string $body): bool
    {
        if ($this->mode === 'mail') {
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/plain;charset=UTF-8\r\n";
            $headers .= "From: noreply@vitegourmand.fr\r\n";
            return @mail($to, $subject, $body, $headers);
        }

        // Mode log
        $dir = __DIR__ . '/../../storage';
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $log = "=== EMAIL ===\n";
        $log .= "TO: $to\nSUBJECT: $subject\n\n$body\n";
        $log .= "=============\n\n";

        return file_put_contents($dir . '/mail.log', $log, FILE_APPEND) !== false;
    }
}
