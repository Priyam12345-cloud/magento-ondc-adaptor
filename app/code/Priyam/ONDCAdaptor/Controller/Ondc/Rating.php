<?php
namespace Priyam\ONDCAdaptor\Controller\Ondc;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Rating extends Action
{
    protected $resultJsonFactory;

    public function __construct(Context $context, JsonFactory $resultJsonFactory)
    {
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $response = [
            "message" => "Rating API called",
            "rating_status" => "received"
        ];

        return $this->resultJsonFactory->create()->setData($response);
    }
}
