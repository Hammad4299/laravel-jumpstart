<?php

namespace App\Trigger;

class TriggerContext {
    /**
     * @var null|number
     */
    protected $locationId;
    /**
     * @var null|number
     */
    protected $clientId;
    /**
     * @var null|number
     */
    protected $kioskId;

    public function __construct($locationId = null, $kioskId = null, $clientId = null)
    {
        $this->setLocationId($locationId);
    }

    /**
     * Get the value of kioskId
     *
     * @return  null|number
     */ 
    public function getKioskId()
    {
        return $this->kioskId;
    }

    /**
     * Set the value of kioskId
     *
     * @param  null|number  $kioskId
     *
     * @return  self
     */ 
    public function setKioskId($kioskId)
    {
        $this->kioskId = $kioskId;

        return $this;
    }

    /**
     * Get the value of clientId
     *
     * @return  null|number
     */ 
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set the value of clientId
     *
     * @param  null|number  $clientId
     *
     * @return  self
     */ 
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get the value of locationId
     *
     * @return  null|number
     */ 
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * Set the value of locationId
     *
     * @param  null|number  $locationId
     *
     * @return  self
     */ 
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;

        return $this;
    }
}