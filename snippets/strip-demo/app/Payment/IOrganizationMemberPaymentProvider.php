<?php

namespace App\Payment;
use App\Models\Organization;

/**
 * Created by PhpStorm.
 * User: talha
 * Date: 4/7/2018
 * Time: 12:22 AM
 */
interface IOrganizationMemberPaymentProvider
{
    /**
     * @param Organization $organization
     * @return \App\Classes\AppResponse
     */
    function saveUpdatePaymentInfo(Organization $organization, $data);

    /**
     * @param Organization $organization
     * @return \App\Classes\AppResponse
     */
    function subscribe(Organization $organization);

    function cancelSubscription($organization, $subscription);

    function updateMembersCount($organization, $quantity, $subscription);

    function getTransactions();
}