<?php

namespace App\Classes\Notification\Receipt;

use App\Classes\Notification\SenderType;


class GenericReceipt implements SMSReceiptContract, EmailReceiptContract {
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $phonenumber;
    protected $destinationPhoneNumber;
    protected $destinationEmail;
    protected $streetAddress;
    protected $city;
    protected $zip;
    protected $state;
    protected $country;

    function getFirstName() {
        return $this->firstname;
    }

    function getLastName() {
        return $this->lastname;
    }
    function getEmail() {
        return $this->email;
    }

    function getPhonenumber() {
        return $this->phonenumber;
    }

    function getDestinationPhoneNumber() {
        return $this->destinationPhoneNumber;
    }

    function getDestinationEmail() {
        return $this->destinationEmail;
    }

    function getStreetAddress() {
        return $this->streetAddress;
    }

    function getCity() {
        return $this->city;
    }

    function getZip() {
        return $this->zip;
    }

    function getState() {
        return $this->state;
    }

    function getCountry() {
        return $this->country;
    }

    function setFirstName($val) {
        $this->firstname = $val;
    }

    function setLastName($val) {
        $this->lastname = $val;
    }
    function setEmail($val) {
        $this->email = $val;
    }

    function setPhonenumber($val) {
        $this->phonenumber = $val;
    }

    function setDestinationPhoneNumber($val) {
        $this->destinationPhoneNumber = $val;
    }

    function setDestinationEmail($val) {
        $this->destinationEmail = $val;
    }

    function setStreetAddress($val) {
        $this->streetAddress = $val;
    }

    function setCity($val) {
        $this->city = $val;
    }

    function setZip($val) {
        $this->zip = $val;
    }

    function setState($val) {
        $this->state = $val;
    }

    function setCountry($val) {
        $this->country = $val;
    }

    function getCompatibleSenderTypes() {
        return [
            SenderType::TYPE_SMS
        ];
    }

    /**
     * @param Visitor $visitor
     * @return GenericReceipt
     */
    public static function createFromVisitor($visitor) {
        $receipt = new GenericReceipt();
        $receipt->setFirstName($visitor->first_name);
        $receipt->setLastName($visitor->last_name);
        $receipt->setPhonenumber($visitor->phone);
        $receipt->setDestinationPhoneNumber($visitor->phone);
        $receipt->setEmail($visitor->email);
        $receipt->setDestinationEmail($visitor->destinationEmail);
        return $receipt;
    }
}