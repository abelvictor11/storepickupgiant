<?php

namespace BikeExchange\FixStoreLocator\Plugin\Model;

class ValidateShipping
{

    protected $_productRepository;
    protected $_cart;

    public function __construct(
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Checkout\Model\Cart $cart
    )
    {

        $this->_productRepository = $productRepository;
        $this->cart = $cart;
    }

    public function hasBikeCategory() {

        $hasBike = false;
        $bikeCategoryId = 3;

        $products = $this->_cart->getQuote()->getItemsCollection();
        foreach ($products as $product) {

            $productId = $product->getProductId();

            $productData = $_productRepository->getById($productId);

            $categoryList = $productData->getCategoryIds(); 
            if (is_array($categoryList)) {
                if (in_array($bikeCategoryId,$categoryList)) {
                    $hasBike = true;
                }
    
            }
        }       
        
        return $hasBike;


    }


    public function aroundCollectCarrierRates(
        \Magento\Shipping\Model\Shipping $subject,
        \Closure $proceed,
        $carrierCode,
        $request
    )
    {

        $hasBikeCategory = $this->hasBikeCategory();

        $writer = new \Zend\Log\Writer\Stream(BP.'/var/log/magento2.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);     
        $logger->info("NUevo Inside " . $carrierCode);
        //return false;
        
        if ($carrierCode == 'flatrate') {
            $logger->info("Inside" );
            if ($hasBikeCategory) {
                return false;
            }
        } else if ($carrierCode == 'amstorepickup') {
            if (!$hasBikeCategory) {
                return false;
            }
        }  else {
            $logger->info("No Inside" );
        }
           // To enable the shipping method
        return $proceed($carrierCode, $request);
    }
}