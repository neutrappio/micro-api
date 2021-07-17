<?php
declare(strict_types=1);

namespace Mapi\Providers;

use Closure;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

use Mapi\Core\Provider;
use Mapi\Interfaces\IApplication;

class JWTProvider extends Provider
{
    const SERVICE_NAME = "jwt";

    public function init(IApplication $application) : Closure
    {
        return function () {
            $config = $this->getConfig();

            $secretKey = InMemory::base64Encoded($config->jwt->secretkey);
            $config = Configuration::forSymmetricSigner(
                // You may use any HMAC variations (256, 384, and 512)
                new Sha256(),
                // replace the value below with a key of your own!
                $secretKey
                // You may also override the JOSE encoder/decoder if needed by providing extra arguments here
            );

            return [
                'key'=> $secretKey,
                'config'=> $config // instnace Configuration::class ready to use
            ];
        };
    }
}
