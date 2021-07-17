<?php

namespace Mapi\Libraries\Mailer;

use Exception;
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_SendmailTransport;

use Mapi\Libraries\Mailer\Config;

class Mailer
{
    /**
     * Mailer Configs
     *
     * @var Config
     */
    private Config $configs;
    
    private bool $init = false;
    private $mailer;
    private $transport;

    /**
     * Init Mailer Service
     *
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $this->configs = new Config($configs);
    }

    private function initMailer()
    {
        if (!$this->init) {
            switch ($this->configs->driver) {
                case 'smtp':
                    $this->transport = (new Swift_SmtpTransport($this->configs->host, $this->configs->port))
                    ->setUsername($this->configs->username)
                    ->setPassword($this->configs->password);
                    break;
                case 'sendmail':
                    $this->transport = new Swift_SendmailTransport($this->configs->sendmail ??'/usr/sbin/sendmail -bs');
                    break;
            }
            
            $this->mailer = new Swift_Mailer($this->transport);
        }

        $this->init = true;
    }

    /**
     * Create New Message instance
     *
     * @return Message
     */
    public function createMessage() : Message
    {
        $this->initMailer();

        $this->message = (new Message())->setMailer($this->mailer);
        $this->message->setFrom([$this->configs->from->email => $this->configs->from->name]);

        return $this->message;
    }


    /**
     * Send Mail view
     *
     * @param string $reciver
     * @param string $subject
     * @param string $viewFile
     * @param array $values
     * @return void
     */
    public function sendMailTemplate(string $reciver, string $subject, string $viewFile, array $values)
    {
        return $this->createMessage()
            ->setSubject($subject)
            ->setTo($reciver)
            ->setBody($this->generateView($viewFile, $values), 'text/html')
            ->sendMail();
    }

    /**
     * Generate view from fileView and Array of variables
     *
     * @param string $viewFile
     * @param array $values
     * @return string
     */
    public function generateView(string $viewFile, array $values) : string
    {
        $values['app.name'] = $_ENV["APP_NAME"] ?? "DMApp";

        $keyValues = [];
        foreach ($values as $key => $value) {
            $keyValues["{{{$key}}}"] = $value;
        }

        return strtr($this->getViewContent($viewFile), $keyValues);
    }

    /**
     * Get View Content
     *
     * @param string $viewFile
     * @return string
     */
    public function getViewContent(string $viewFile) : string
    {
        $filePath = $this->configs->views . "/" . $viewFile;


        if (!is_file($filePath)) {
            throw new Exception("Mail view file is not found! $filePath");
        }

        return (string) file_get_contents($filePath);
    }
}
