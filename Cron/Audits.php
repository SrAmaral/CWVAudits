<?php

namespace Webjump\CWVAudit\Cron;

use Webjump\CWVAudit\Logger\Logger;
use Webjump\CWVAudit\Model\ResourceModel\CWVAudit\Collection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\AsyncClientInterface;
use Magento\Framework\HTTP\AsyncClient\Request;

class Audits
{
    public function __construct(
        private Logger $logger,
        private Collection $collection,
        private ScopeConfigInterface $scopeConfig,
        private AsyncClientInterface $asyncClient,
        private Request $request
    ){}

        public function execute()
    {

        $urlsConfig = $this->scopeConfig->getValue(
            'cwvaudit/general/urls',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $urlsAudits = explode(',', preg_replace('/\s+/', '', $urlsConfig));
        $sanatizedUrls = array_filter($urlsAudits, 'strlen');


        $result = $this->asyncClient->request(new Request("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url={$sanatizedUrls[0]}&strategy=mobile", 'GET', ['Accept' => 'application/json'], null));

        $this->logger->info(json_encode($result->get()->getBody()));
        $this->logger->info('aii papito');
    }

}
