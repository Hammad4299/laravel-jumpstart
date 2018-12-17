<?php

namespace App\Payment;

use App\Classes\AppResponse;
use App\Classes\Helper;
use App\Models\Organization;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Subscription;
use Stripe\Webhook;


/**
 * Created by PhpStorm.
 * User: talha
 * Date: 4/7/2018
 * Time: 12:31 AM
 */
class StripeOrganizationMemberPaymentProvider implements IOrganizationMemberPaymentProvider
{
    const ERROR_FIELD = 'payment_error';
    protected $plans;
    protected $webhookSigningSecret;

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $this->webhookSigningSecret = config('services.stripe.webhook_signing_secret');
        $this->plans = config('services.stripe.plans.organization_members');
    }

    protected function getPlansForQuantity($quantity)
    {
        $subscriptionPlans = [];
        foreach ($this->plans as $plan) {
            $toUse = $plan['max'] === null ? $quantity : min($quantity, $plan['max']);
            $subscriptionPlans[] = [
                'plan' => $plan['id'],
                'quantity' => $toUse
            ];

            $quantity -= $toUse;
        }

        return $subscriptionPlans;
    }

    protected function execute(AppResponse $resp, \Closure $callback)
    {
        try {
            $callback();
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            $resp->addError(self::ERROR_FIELD, $err['message']);
        } catch (\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
        } catch (\Exception $e) {
            $resp->addError(self::ERROR_FIELD, 'Error processing payment request');
            throw $e;
        }
    }

    protected function setOrganizationPaymentInfo($organization, $stripeCustomer)
    {
        $organization->stripe_customer_id = $stripeCustomer->id;
        $sources = $stripeCustomer->sources->data;
        if (count($sources) > 0) {
            $source = $sources[0];
            $organization->card_last_four = $source->last4;
        }
        $organization->save();
        return $organization;
    }

    /**
     * @param \App\Models\Organization $organization
     * @param $data
     * @return \App\Classes\AppResponse contain
     * @throws \Exception
     */
    function saveUpdatePaymentInfo(Organization $organization, $data)
    {
        $resp = new AppResponse(true);
        if (empty($organization->stripe_customer_id)) {
            $this->execute($resp, function () use ($resp, $data, $organization) {
                $c = Customer::create([
                    'source' => Helper::getWithDefault($data, 'stripeToken'),
                    'metadata' => [
                        'organization_id' => $organization->id
                    ]
                ]);

                $resp->data = $this->setOrganizationPaymentInfo($organization, $c);
            });
        } else {
            $this->execute($resp, function () use ($resp, $data, $organization) {
                $c = Customer::update($organization->stripe_customer_id, [
                    'source' => Helper::getWithDefault($data, 'stripeToken')
                ]);

                $resp->data = $this->setOrganizationPaymentInfo($organization, $c);
            });
        }

        return $resp;
    }

    public function getWebhookEvent($payload, $signatureHeader)
    {
        $resp = new AppResponse(true);
        $endpoint_secret = $this->webhookSigningSecret;
        $sig_header = $signatureHeader;
        $resp->data = null;

        try {
            $resp->data = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            throw $e;
        } catch (\Stripe\Error\SignatureVerification $e) {
            throw $e;
        }

        return $resp;
    }

    public function getCustomer($id)
    {
        $resp = new AppResponse(true);
        $this->execute($resp, function () use ($resp, $id) {
            $c = Customer::retrieve($id);
            $resp->data = $c;
        });

        return $resp;
    }

    /**
     * @param \App\Models\Organization $organization
     * @return \App\Classes\AppResponse
     * @throws \Exception
     */
    function subscribe(Organization $organization)
    {
        $resp = new AppResponse(true);
        if (!empty($organization->stripe_customer_id)) {
            $this->execute($resp, function () use ($organization, &$resp) {
                $c = Subscription::create([
                    'customer' => $organization->stripe_customer_id,
                    'items' => $this->getPlansForQuantity(1)
                ]);

                $resp->data = [
                    'stripe_subscription_id' => $c->id
                ];
            });
        } else {
            $resp->addError(self::ERROR_FIELD, 'Missing payment information');
        }

        return $resp;
    }

    function cancelSubscription($organization, $subscription)
    {
        if ($subscription) {
            $org = $organization;
            if ($org) {
                $c = Customer::retrieve($org->stripe_customer_id);
//                if ($c) {
//                    try {
                        $source = $c->sources->retrieve($c->default_source);
                        if ($source) {
                            $source->delete();
                        }
//                    } catch (\Exception $e) {
//                    }
//                }


//                try {
                    $s = Subscription::retrieve($subscription->stripe_subscription_id);
                    if ($s) {
                        $s->cancel();
                    }
//                } catch (\Exception $e) {
//
//                }
            }
        }
    }

    function getTransactions()
    {
        // TODO: Implement getTransactions() method.
    }

    function updateMembersCount($organization, $quantity, $orgSubscription)
    {
        $resp = new AppResponse(true);
        if (!empty($organization->stripe_customer_id) && $orgSubscription) {
            $this->execute($resp, function () use ($orgSubscription, $quantity, $organization) {
                $c = Subscription::retrieve($orgSubscription->stripe_subscription_id);
                $plans = $this->getPlansForQuantity($quantity);
                $planQuantityMap = [];

                foreach ($plans as $plan) {
                    $planQuantityMap[$plan['plan']] = $plan['quantity'];
                }

                foreach ($c->items->data as $d) {
                    $plan = $d->plan;
                    $preQuantity = $d->quantity;
                    $d->quantity = $planQuantityMap[$plan->id];
                    if ($preQuantity != $d->quantity) {
                        $d->save();
                    }
                }
            });
        } else {
            $resp->addError(self::ERROR_FIELD, 'Missing payment information');
        }

        return $resp;
    }
}