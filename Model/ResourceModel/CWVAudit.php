<?php

namespace Webjump\CWVAudit\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CWVAudit extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'cwv_audit_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('cwv_audit', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
