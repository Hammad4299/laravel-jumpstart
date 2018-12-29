<?php

namespace App\Classes\Notification\Receipt;

interface EmailReceiptContract extends ReceiptContract {
    function getFirstName();
    function getLastName();
    function getEmail();
    function getDestinationEmail();
}