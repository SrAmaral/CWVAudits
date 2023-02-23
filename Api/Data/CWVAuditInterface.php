<?php declare(strict_types=1);

namespace Webjump\CWVAudit\Api\Data;

/**
 * Blog post interface.
 * @api
 * @since 1.0.0
 */
interface CWVAuditInterface
{
    const ID = 'entity_id';
    const URL = 'url';
    const PERFORMACE = 'performace';
    const FRIST_CONTENT_PAINT = 'Frist_Content_Paint';
    const SPEED_INDEX = 'Speed_Index';
    const LARGEST_CONTENTFUL_PAINT = 'Largest_Contentful_Paint';
    const TIME_TO_INTERACTIVE = 'Time_To_Interactive';
    const TOTAL_BLOCKING_TIME = 'Total_Blocking_Time';
    const CUMULATIVE_LAYOUT_SHIFT = 'Cumulative_Layout_Shift';
    const UPDATED_AT = 'updated_at';


    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param int $value
     * @return $this
     */
    public function setId($value);

    /**
     * @return mixed
     */
    public function getUrl();

    /**
     * @param int $value
     * @return $this
     */
    public function setUrl($url);

    /**
     * @return mixed
     */
    public function getPerformace();

    /**
     * @param int $value
     * @return $this
     */
    public function setPerformace($performace);

    /**
     * @return mixed
     */
    public function getFirstContentPaint();

    /**
     * @param int $value
     * @return $this
     */
    public function setFirstContentPaint($fcp);

    /**
     * @return mixed
     */
    public function getSpeedIndex();

    /**
     * @param int $value
     * @return $this
     */
    public function setSpeedIndex($speedIndex);

    /**
     * @return mixed
     */
    public function getLargestContentPaint();

    /**
     * @param int $value
     * @return $this
     */
    public function setLargestContentPaint($lcp);

    /**
     * @return mixed
     */
    public function getTimeToInteractive();

    /**
     * @param int $value
     * @return $this
     */
    public function setTimeToInteractive($tti);

    /**
     * @return mixed
     */
    public function getTotalBlockingTime();

    /**
     * @param int $value
     * @return $this
     */
    public function setTotalBlockingTime($tbt);

    /**
     * @return mixed
     */
    public function getCumulativeLayoutShift();

    /**
     * @param int $value
     * @return $this
     */
    public function setCumulativeLayoutShift($cls);

    /**
     * @return mixed
     */
    public function getUpdateAt();

    /**
     * @param int $value
     * @return $this
     */
    public function setUpdateAt($updateAt);

}
