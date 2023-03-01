<?php
namespace Webjump\CWVAudit\Api;


use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Webjump\CWVAudit\Api\Data\CWVAuditInterface;

interface CWVAuditRepositoryInterface {


    /**
     * @param int $id
     * @return CWVAuditInterface
     * @throws LocalizedException
     */
    public function getById(int $id): CWVAuditInterface;

    /**
     * @return CWVAuditInterface[] | null
     */
    public function getList();

    /**
     * @return CWVAuditInterface[] | null
     */
    public function getByUrl();

    /**
     * @return mixed
     */
    public function getHomeUrl();

    /**
     * @param CWVAuditInterface $audit
     * @return CWVAuditInterface
     * @throws LocalizedException
     */
    public function save(CWVAuditInterface $audit): CWVAuditInterface;

    /**
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById(int $id): bool;


}