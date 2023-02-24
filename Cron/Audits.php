<?php

namespace Webjump\CWVAudit\Cron;

use Webjump\CWVAudit\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\AsyncClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseFactory;
use Magento\Framework\Webapi\Rest\Request;

class Audits
{

    const API_REQUEST_URI = 'https://www.googleapis.com';
    const API_REQUEST_ENDPOINT = 'pagespeedonline/v5/runPagespeed';
    public function __construct(
        private Logger $logger,
        private ScopeConfigInterface $scopeConfig,
        private ClientFactory $clientFactory,
        private ResponseFactory $responseFactory
    ){}

        public function execute()
    {

        $urlsConfig = $this->scopeConfig->getValue(
            'cwvaudit/general/urls',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $urlsAudits = explode(',', preg_replace('/\s+/', '', $urlsConfig));
        $sanatizedUrls = array_filter($urlsAudits, 'strlen');



        foreach($sanatizedUrls as $url){
            $params = "?url=https://{$url}&strategy=mobile";
            $response = $this->doRequest(static::API_REQUEST_ENDPOINT, $params);
            $status = $response->getStatusCode();
            $responseBody = $response->getBody();
            $responseContent = json_decode($responseBody->getContents(), true);
            $this->logger->info($url);
            $this->logger->info($status);



            $metricsAudit = [
                "page" => $url,
                "performace" => number_format($responseContent["lighthouseResult"]["categories"]["performance"]["score"]*100, 0, '.', ''),
                "Frist_Content_Paint" => number_format($responseContent["lighthouseResult"]["audits"]["first-contentful-paint"]["numericValue"]/1000, 1, '.', ''),
                "Speed_Index" => number_format($responseContent["lighthouseResult"]["audits"]["speed-index"]["numericValue"]/1000, 1, '.', ''),
                "Largest_Contentful_Paint" => number_format($responseContent["lighthouseResult"]["audits"]["largest-contentful-paint"]["numericValue"]/1000, 1, '.', ''),
                "Time_To_Interactive" => number_format($responseContent["lighthouseResult"]["audits"]["interactive"]["numericValue"]/1000, 1, '.', ''),
                "Total_Blocking_Time" => number_format($responseContent["lighthouseResult"]["audits"]["total-blocking-time"]["numericValue"], 3, '.', ''),
                "Cumulative_Layout_Shift" => number_format($responseContent["lighthouseResult"]["audits"]["cumulative-layout-shift"]["numericValue"], 3, '.', ''),
            ];

            $this->logger->info(json_encode($metricsAudit));
        }
    }

    private function doRequest(
        string $uriEndpoint,
        string $params = '',
        string $requestMethod = Request::HTTP_METHOD_GET
    ): Response {

        $paramss = json_encode($params);
        /** @var Client $client */
        $client = $this->clientFactory->create(['config' => [
            'base_uri' => self::API_REQUEST_URI
        ]]);
        try {
            $response = $client->request(
                $requestMethod,
                $uriEndpoint.$params,
            );
        } catch (GuzzleException $exception) {
            /** @var Response $response */
            $response = $this->responseFactory->create([
                'status' => $exception->getCode(),
                'reason' => $exception->getMessage()
            ]);
            $this->logger->info($exception->getMessage());
        }

        return $response;
    }

}
