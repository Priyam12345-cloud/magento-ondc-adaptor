<?php
namespace Priyam\ONDCAdaptor\Controller\Ondc;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Framework\HTTP\Client\Curl;
use Psr\Log\LoggerInterface;

class Search extends Action
{
    protected $resultJsonFactory;
    protected $productCollectionFactory;
    protected $curl;
    protected $logger;
    protected $imageHelper;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CollectionFactory $productCollectionFactory,
        Curl $curl,
        LoggerInterface $logger,
        ImageHelper $imageHelper
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->curl = $curl;
        $this->logger = $logger;
        $this->imageHelper = $imageHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        try {
            // 🔹 1. Fetch real Magento products
            $collection = $this->productCollectionFactory->create()
                ->addAttributeToSelect(['name', 'price', 'image'])
                ->addMediaGalleryData()
                ->setPageSize(10);

            $catalogItems = [];

            foreach ($collection as $product) {
                // Get proper image URL
                $imageUrl = '';
                $images = $product->getMediaGalleryImages();
                if ($images && $images->getSize()) {
                    $imageUrl = $images->getFirstItem()->getUrl();
                }

                $catalogItems[] = [
                    "id" => (string)$product->getId(),
                    "descriptor" => [
                        "name" => $product->getName(),
                        "images" => [$imageUrl]
                    ],
                    "price" => [
                        "currency" => "INR",
                        "value" => (string)$product->getPrice()
                    ],
                    "category_id" => $product->getCategoryIds()[0] ?? "General",
                    "tags" => ["Magento"]
                ];
            }

            // 🔹 2. Send to ONDC Node adapter
            $payload = ["items" => $catalogItems];
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->post("http://localhost:3000/search", json_encode($payload));
            $response = $this->curl->getBody();

            // 🔹 3. Return ONDC response
            return $result->setData(json_decode($response, true));

        } catch (\Exception $e) {
            $this->logger->error("ONDCAdaptor error: " . $e->getMessage());
            return $result->setData(["error" => $e->getMessage()]);
        }
    }
}
