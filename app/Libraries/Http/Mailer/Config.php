<?php

namespace Mapi\Libraries\Mailer;

use stdClass;

class Config
{
    
    /**
     * Mailer Views directory
     *
     * @var string
     */
    public string $views;
    
    /**
     * Mailer Driver
     *
     * @var string
     */
    public string $driver;

    /**
     * Mailer Port
     *
     * @var int
     */
    public int $port;
    
    /**
     * Mailer Host
     *
     * @var string
     */
    public string $host;
    
    /**
     * Encryption
     *
     * @var string
     */
    public string $encryption;
    
    /**
     * Mailer Username
     *
     * @var string
     */
    public string $username;
    
    /**
     * Mailer Password
     *
     * @var string
     */
    public string $password;
    
    /**
     * Mailer From Name
     *
     * @var string
     */
    public \stdClass $from;

    /**
     * Init Configs
     *
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        foreach ($configs as $key=> $value) {
            if (property_exists($this, $key)) {
                if ($key === 'from') {
                    $this->initFrom($value);
                    break;
                }
                $this->$key = $value;
            }
        }
    }

    /**
     * Get Configs to Array
     *
     * @return array
     */
    public function toArray() : array
    {
        $configs = [];

        foreach (get_class_vars(self::class) as $key) {
            $configs[$key] = $this->$key;
        }

        return $configs;
    }

    private function initFrom(array $from) : void
    {
        $this->from = new stdClass();

        $this->from->name = $from['name'] ?? null;
        $this->from->email = $from['email'] ?? null;
    }
}
