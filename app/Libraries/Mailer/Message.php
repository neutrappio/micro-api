<?php

namespace Mapi\Libraries\Mailer;

use Swift_Message;

class Message extends Swift_Message
{
    private $mailer;

    /**
     * Set Mailer
     *
     * @param Mailer $mailer
     * @return self
     */
    public function setMailer($mailer) : self
    {
        $this->mailer = $mailer;

        return $this;
    }

    /**
     * Send Mail
     *
     * @return void
     */
    public function sendMail()
    {
        return $this->mailer->send($this);
    }
}
