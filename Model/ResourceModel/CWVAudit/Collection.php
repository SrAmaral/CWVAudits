<?php

namespace Webjump\CWVAudit\Model\ResourceModel\CWVAudit;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Webjump\CWVAudit\Model\CWVAudit as Model;
use Webjump\CWVAudit\Model\ResourceModel\CWVAudit as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'cwv_audit_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
