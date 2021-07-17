<?php

namespace Mapi\Core;

use DateTime;
use DateInterval;
use Phalcon\Security\Random;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Mvc\Model as PModel;

use Mapi\Interfaces\ISharedConst;
use Mapi\Libraries\Http\HttpCodes;
use Mapi\Exceptions\PublicException;

abstract class Model extends PModel implements ISharedConst
{
    /**
     * Generate auto ID
     *
     * @var bool
     */
    const ID_GENERATE = true;
    
    /**
     * Basic fields to getData
     *
     * @var array
     */
    const BASIC_FIELDS = [];

    /** @var bool */
    const ID_VALIDATOR = true;


    /**
     * Client IP
     *
     * @var string
     */
    protected $client_ip = null;

    
    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Model[]|Model|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Model|\Phalcon\Mvc\Model\ResultInterface|ModelInterface|null
     */
    public static function findFirst($parameters = null): ?ModelInterface
    {
        return parent::findFirst($parameters);
    }

    /**
     * Find First By Id : Validate UUID
     *
     * @param string $id
     * @return \Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirstById(?string $id) : ?ModelInterface
    {
        if (is_null($id) || true === get_called_class()::ID_VALIDATOR && false === self::validUUID($id)) {
            throw new PublicException("invalid id", HttpCodes::HTTP_BAD_REQUEST);
        }
        
        return parent::findFirstById($id);
    }

    /**
     * Set Default Schema Name
     *
     * @return void
     */
    public function setDefaultSchema() : void
    {
        $this->setSchema($this->getDI()->config->database->schema ?? "public");
    }

    /**
     * Before Create , Save microtime into database
     *
     * @return void
     */
    public function beforeCreate() : void
    {
        /**
         * Generate new UUID
         */
        $this->generateUUID();

        /**
         * Fill Meta Time
         */
        $selfClass = get_called_class();

        if (property_exists($selfClass, 'created_at')) {
            $this->created_at = self::getTime();
        }
        if (property_exists($selfClass, 'created_ip')) {
            $this->created_ip = $this->client_ip;
        }
    }
    
    /**
     * Before update , Save microtime into database
     *r
     * @return void
     */
    public function beforeUpdate() : void
    {
        $selfClass = get_called_class();

        if (property_exists($selfClass, 'updated_at')) {
            $this->updated_at = self::getTime();
        }
        if (property_exists($selfClass, 'updated_ip')) {
            $this->updated_ip = $this->client_ip;
        }
    }

    /**
     * Before Delete , Save microtime into database
     *
     * @return void
     */
    public function beforeDelete() : void
    {
        $selfClass = get_called_class();

        if (property_exists($selfClass, 'deleted_at')) {
            $this->deleted_at = self::getTime();
        }
        if (property_exists($selfClass, 'deleted_ip')) {
            $this->deleted_ip = $this->client_ip;
        }
    }

    /**
     * Set Client Ip
     *
     * @param String $clientIp
     * @return self
     */
    public function setIp(string $clientIp) : self
    {
        $this->client_ip = $clientIp;
        return $this;
    }

    /**
     * Get Client Ip
     *
     * @return string
     */
    public function getIp() : string
    {
        return  (string) ($this->client_ip ?? $this->getDI()->get('request')->getClientAddress() ?? null);
    }

    /**
     * Get Current Timestamp
     *
     * *Example $addTime : 'P7Y5M4D' +7Years + 5 Months + 4 Days*
     * @param string|null $addTime Add time to current datetime
     * @return string|null
     */
    public static function getTime(string $addTime = null) :? string
    {
        $date = new DateTime('now');

        if (null !== $addTime) {
            $date->add(new DateInterval($addTime));
        }

        return substr($date->format('Y-m-d H:i:s.u'), 0, 22);
    }

    /**
     * Validate an UUID
     *
     * @param string $uuid
     * @return boolean
     */
    public static function validUUID(string $uuid) : bool
    {
        $matches = preg_match('/^[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-f]{12}$/i', $uuid);
        return $matches >= 1;
    }

    /**
     * Set Model Status
     *
     * @param boolean $active
     * @return self
     */
    public function setActive(bool $active) : self
    {
        $this->status = true === $active ? self::ACTIVE : self::INACTIVE;
        return $this;
    }

    /**
     * Generate New UUID
     *
     * @return self
     */
    protected function generateUUID() : self
    {
        $class = get_called_class();
        
        if (true === property_exists($class, 'id') && $class::ID_GENERATE) {
            $this->id = (new Random())->uuid();
        }

        return $this;
    }

    /**
     * Get Basic Data
     *
     * @param array $fields include more fields
     * @return array
     */
    public function getData(array $fields = []) : array
    {
        $data = [];

        $fields += $this::BASIC_FIELDS;

        foreach ($fields as $key) {
            $value = $this->$key ?? null;
            
            if (is_object($value)) {
                $value = $value->getData();
            }

            $data[$key] = $value;
        }

        return $data;
    }
}
