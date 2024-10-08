<?php

namespace Saqqal\LlmIntegrationBundle\Tests\Unit\Client;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\Client\ApiTogetherClient;
use Saqqal\LlmIntegrationBundle\Exception\AiClientException;
use Saqqal\LlmIntegrationBundle\Response\AiResponse;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiTogetherClientTest extends TestCase
{
    private ApiTogetherClient $apiTogetherClient;

    protected function setUp(): void
    {
        $httpClient = HttpClient::create();
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->apiTogetherClient = new ApiTogetherClient(
            $httpClient,
            $eventDispatcher,
            "API_KEY", // Make sure to set this in your .env.test file
            "meta-llama/Meta-Llama-3.1-8B-Instruct-Turbo" // Make sure to set this in your .env.test file
        );
    }

    /**
     * @throws AiClientException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testSendPrompt()
    {
        $response = $this->apiTogetherClient->sendPrompt('Hello, world!');
        $this->assertInstanceOf(AiResponse::class, $response);
        $this->assertEquals('success', $response->getStatus());
        $this->assertNotEmpty($response->getData()['content']);
        $this->assertNotEmpty($response->getMetadata()['id']);
        $this->assertNotEmpty($response->getMetadata()['model']);
    }
}
