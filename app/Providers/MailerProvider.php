<?php
declare(strict_types=1);

namespace Mapi\Providers;

use Closure;

use Mapi\Core\Provider;
use Mapi\Interfaces\IApplication;
use Mapi\Libraries\Mailer\Mailer;

class MailerProvider extends Provider
{
    const SERVICE_NAME = "mail";

    public function init(IApplication $application) : Closure
    {
        return function () {
            $configs = $this->getConfig()->mailer;
    
            return new Mailer($configs->toArray());
        };
    }
}
