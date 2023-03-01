<?php
namespace Webjump\CWVAudit\Model;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Webjump\CWVAudit\Api\CWVAuditRepositoryInterface;
use Webjump\CWVAudit\Api\Data\CWVAuditInterface;
use Webjump\CWVAudit\Model\ResourceModel\CWVAudit\Collection;
use Webjump\CWVAudit\Model\ResourceModel\CWVAudit as CWVAuditResourceModel;

class CWVAuditRepository implements CWVAuditRepositoryInterface {

    public function __construct(
        private CWVAuditFactory $auditFactory,
        private ScopeConfigInterface $scopeConfig,
        private CWVAuditResourceModel $auditResourceModel,
        private Collection $collection
    ) {}


    public function getById(int $id): CWVAuditInterface
    {
        $audit = $this->auditFactory->create();
        $this->auditResourceModel->load($audit, $id);

        if (!$audit->getId()) {
            throw new NoSuchEntityException(__('The todo item with the "%1" ID doesn\'t exist.', $id));
        }

        return $audit;
    }

    public function getList()
    {
        $audits = $this->collection->getItems();



        return $audits;
    }

    public function getByUrl()
    {
        // TODO: Implement getByUrl() method.
    }

    public function save(CWVAuditInterface $audit): CWVAuditInterface
    {
        try {
            $this->auditResourceModel->save($audit);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $audit;
    }

    public function deleteById(int $id): bool
    {
        return 1;
    }

    public function getHomeUrl()
    {
        $urlsConfig = $this->scopeConfig->getValue(
            'cwvaudit/general/url_home',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $homeUrl = explode(',', preg_replace('/\s+/', '', $urlsConfig));

        return $homeUrl;
    }
}