<?php

namespace App\Receipt;

use App\Models\Visitor;
use App\Models\Employee;

class ReceiptContext {
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

    /**
     * Undocumented variable
     *
     * @var Visitor
     */
    protected $visitor;

    /**
     * Undocumented variable
     *
     * @var Employee
     */
    protected $host;

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

    /**
     * Get undocumented variable
     *
     * @return  Employee
     */ 
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set undocumented variable
     *
     * @param  Employee  $host  Undocumented variable
     *
     * @return  self
     */ 
    public function setHost(Employee $host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  Visitor
     */ 
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * Set undocumented variable
     *
     * @param  Visitor  $visitor  Undocumented variable
     *
     * @return  self
     */ 
    public function setVisitor(Visitor $visitor)
    {
        $this->visitor = $visitor;

        return $this;
    }
}