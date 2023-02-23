<?php

namespace Webjump\CWVAudit\Model;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Webjump\CWVAudit\Model\ResourceModel\CWVAudit\CollectionFactory;
use Magento\Framework\Registry;

class DataProvider extends AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $messagesCollectionFactory
     * @param Registry $registry
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $messagesCollectionFactory,
        Registry $registry,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $messagesCollectionFactory->create();
        $this->registry = $registry;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        $data = parent::getData();
        $display = $this->registry->registry('cwv_audit');
        if ($display->getData()) {
            $data[$display->getEntityId()] = $display->getData();
        }
        return $data;
    }
}
