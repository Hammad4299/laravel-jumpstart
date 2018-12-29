<?php

namespace App\Classes\Notification\Receipt;

interface SMSReceiptContract extends ReceiptContract {
    function getFirstName();
    function getLastName();
    function getEmail();
    function getPhonenumber();
    function getDestinationPhoneNumber();
    function getStreetAddress();
    function getCity();
    function getZip();
    function getState();
    function getCountry();
}