<?php

namespace BikeExchange\FixStoreLocator\Plugin\Model;

class ValidateShipping
{

    public function __construct()
    {

    }


    public function aroundCollectCarrierRates(
        \Magento\Shipping\Model\Shipping $subject,
        \Closure $proceed,
        $carrierCode,
        $request
    )
    {

        $writer = new \Zend\Log\Writer\Stream(BP.'/var/log/magento2.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);     
        $logger->info("NUevo Inside " . $carrierCode);
        //return false;

            // Enter Shipping Code here instead of 'freeshipping'
        if ($carrierCode == 'flatrate') {

            $logger->info("Inside" );
           // To disable the shipping method return false
                return false;
        }  else {
            $logger->info("No Inside" );

        }
           // To enable the shipping method
        return $proceed($carrierCode, $request);
    }
}