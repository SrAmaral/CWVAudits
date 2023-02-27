<?php

namespace Webjump\CWVAudit\Model;

use Magento\Framework\Model\AbstractModel;
use Webjump\CWVAudit\Model\ResourceModel\CWVAudit as ResourceModel;
use Webjump\CWVAudit\Api\Data\CWVAuditInterface;

class CWVAudit extends AbstractModel implements CWVAuditInterface{
    /**
     * @var string
     */
    protected $_eventPrefix = 'cwv_audit_model';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @return array|mixed|null
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * @param $url
     * @return CWVAudit
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @return array|mixed|null
     */
    public function getPerformace()
    {
        return $this->getData(self::PERFORMACE);
    }

    /**
     * @param $performace
     * @return CWVAudit
     */
    public function setPerformace($performace)
    {
        return $this->setData(self::PERFORMACE, $performace);
    }

    /**
     * @return array|mixed|null
     */
    public function getFirstContentPaint()
    {
        return $this->getData(self::FRIST_CONTENT_PAINT);
    }

    /**
     * @param $fcp
     * @return CWVAudit
     */
    public function setFirstContentPaint($fcp)
    {
        return $this->setData(self::FRIST_CONTENT_PAINT, $fcp);
    }

    /**
     * @return array|mixed|null
     */
    public function getSpeedIndex()
    {
        return $this->getData(self::SPEED_INDEX);
    }

    /**
     * @param $speedIndex
     * @return CWVAudit
     */
    public function setSpeedIndex($speedIndex)
    {
        return $this->setData(self::SPEED_INDEX, $speedIndex);
    }

    /**
     * @return array|mixed|null
     */
    public function getLargestContentPaint()
    {
        return $this->getData(self::LARGEST_CONTENTFUL_PAINT);
    }

    /**
     * @param $lcp
     * @return CWVAudit
     */
    public function setLargestContentPaint($lcp)
    {
        return $this->setData(self::LARGEST_CONTENTFUL_PAINT, $lcp);
    }

    /**
     * @return array|mixed|null
     */
    public function getTimeToInteractive()
    {
        return $this->getData(self::TIME_TO_INTERACTIVE);
    }

    /**
     * @param $tti
     * @return CWVAudit
     */
    public function setTimeToInteractive($tti)
    {
        return $this->setData(self::TIME_TO_INTERACTIVE, $tti);
    }

    /**
     * @return array|mixed|null
     */
    public function getTotalBlockingTime()
    {
        return $this->getData(self::TOTAL_BLOCKING_TIME);
    }

    /**
     * @param $tbt
     * @return CWVAudit
     */
    public function setTotalBlockingTime($tbt)
    {
        return $this->setData(self::TOTAL_BLOCKING_TIME, $tbt);
    }

    /**
     * @return array|mixed|null
     */
    public function getCumulativeLayoutShift()
    {
        return $this->getData(self::CUMULATIVE_LAYOUT_SHIFT);
    }

    /**
     * @param $cls
     * @return CWVAudit
     */
    public function setCumulativeLayoutShift($cls)
    {
        return $this->setData(self::CUMULATIVE_LAYOUT_SHIFT, $cls);
    }

    public function getJson()
    {
        return $this->getData(self::CUMULATIVE_LAYOUT_SHIFT);
    }

    /**
     * @param $cls
     * @return CWVAudit
     */
    public function setJson($json)
    {
        return $this->setData(self::JSON, $json);
    }



    /**
     * @return array|mixed|null
     */
    public function getUpdateAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @param $updateAt
     * @return CWVAudit
     */
    public function setUpdateAt($updateAt)
    {
        return $this->setData(self::UPDATED_AT, $updateAt);
    }
}
