<?php

namespace App\Classes\Notification\Receipt;

interface SMSReceiptContract extends ReceiptContract {
    function getFirstName();
    function getLastName();
    function getPhonenumber();
    function getDestinationPhoneNumber();
}