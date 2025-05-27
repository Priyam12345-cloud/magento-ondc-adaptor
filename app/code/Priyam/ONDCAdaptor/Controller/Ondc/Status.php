<?php
namespace Priyam\ONDCAdaptor\Controller\Ondc;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Status extends Action
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
            "message" => "Status API called",
            "order_status" => "processing"
        ];

        return $this->resultJsonFactory->create()->setData($response);
    }
}
