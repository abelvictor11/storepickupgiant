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
        $this->_cart = $cart;
    }

    public function hasBikeCategory() {

        $hasBike = false;
        $bikeCategoryId = 3;

        $products = $this->_cart->getQuote()->getItemsCollection();

        foreach ($products as $product) {
            $productId = $product->getProductId();
            $productData = $this->_productRepository->getById($productId);

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


        /*$writer = new \Zend\Log\Writer\Stream(BP.'/var/log/testshipping.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);     
        $logger->info("Entra AQUI");
        $logger->info("Code" . $carrierCode);
        $logger->info("Checkea categoria");*/
         
        $hasBikeCategory = $this->hasBikeCategory();
        if ($carrierCode == 'flatrate') {
            if ($hasBikeCategory) {
                //$logger->info("Deshabilita");
                return false;
            } 
        } else if ($carrierCode == 'amstorepickup') {
            if (!$hasBikeCategory) {
                //$logger->info("Deshabilita");
                return false;
            }
        } 
        
        return $proceed($carrierCode, $request);
    }
}