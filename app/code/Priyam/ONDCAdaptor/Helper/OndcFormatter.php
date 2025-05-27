<?php
namespace Priyam\ONDCAdaptor\Helper;

class OndcFormatter
{
    public function formatProduct($product)
    {
        return [
            "id" => $product->getId(),
            "descriptor" => [
                "name" => $product->getName(),
                "images" => [
                    "https://your-magento-domain/pub/media/catalog/product" . $product->getImage()
                ]
            ],
            "price" => [
                "currency" => "INR",
                "value" => $product->getPrice()
            ],
            "category_id" => "Fashion",
            "tags" => ["Magento"]
        ];
    }
}
