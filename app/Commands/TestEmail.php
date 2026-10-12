<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestEmail extends BaseCommand
{
    protected $group = 'Test';
    protected $name = 'test:email';
    protected $description = 'Test email configuration and sending';

    public function run(array $params)
    {
        CLI::write("Testing with ssl crypto...", "yellow");

        $email = \Config\Services::email(null, false);
        $email->setMailType('html');
        $email->setNewline("\r\n");
        $email->setCRLF("\r\n");
        
        // Test initializing with ssl
        $config = [
            'protocol'   => 'smtp',
            'SMTPHost'   => 'smtp.hostinger.com',
            'SMTPPort'   => 465,
            'SMTPUser'   => 'paclientp@petsfolio.in',
            'SMTPPass'   => '=atcyDUYe?L5',
            'SMTPCrypto' => 'ssl',
            'mailType'   => 'html',
            'charset'    => 'utf-8',
            'newline'    => "\r\n",
            'CRLF'       => "\r\n",
            'wordWrap'   => true,
            'SMTPTimeout'=> 10
        ];
        $email->initialize($config);

        $fromEmail = 'paclientp@petsfolio.in';
        $fromName  = 'Petsfolio App Release';

        $email->setFrom($fromEmail, $fromName);
        $email->setTo('vijay.infasta@gmail.com');
        $email->setSubject('Test Email from Petsfolio CLI (SSL 465)');
        $email->setMessage('<h3>Petsfolio Test</h3><p>Testing SMTP connection via SSL on port 465.</p>');

        CLI::write("Sending...", "yellow");
        if ($email->send(false)) {
            CLI::write("SUCCESS: Email sent successfully via SSL 465!", "green");
        } else {
            CLI::write("FAILURE: Email failed to send.", "red");
            CLI::write($email->printDebugger(['headers', 'subject', 'body']));
        }
    }
}
