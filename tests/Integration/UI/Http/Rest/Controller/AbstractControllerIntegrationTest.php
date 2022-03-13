<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller;

use App\Tests\Integration\Resources\Factory\FakerFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractControllerIntegrationTest extends WebTestCase
{
    protected const GET = 'GET';
    protected const POST = 'POST';
    protected const PUT = 'PUT';
    protected const DELETE = 'DELETE';
    protected const REQUEST_FORMAT = 'json';
    protected KernelBrowser $client;
    /** @var FakerFactoryInterface */
    protected $fakerFactory;
    /** @var EntityManagerInterface */
    protected $_em;
    /** @var NormalizerInterface */
    protected $normalizer;
    /** @var SerializerInterface */
    protected $serializer;

    protected function setUp(): void
    {
        $this->client = $this->createClient();

        $container = static::getContainer();

        $this->fakerFactory = $container->get(FakerFactoryInterface::class);
        $this->_em = $container->get(EntityManagerInterface::class);
        $this->normalizer = $container->get(NormalizerInterface::class);
        $this->serializer = $container->get(SerializerInterface::class);

        $schemaTool = new SchemaTool($this->_em);
        $metadata = $this->_em->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    protected function jsonEncodeAsHttpResponse($data): string
    {
        return json_encode($data, JsonResponse::DEFAULT_ENCODING_OPTIONS);
    }

    protected function sendRequestWithBody(
        string $method,
        string $path,
        mixed $data
    ): Response {
        $this->client->request(
            $method,
            $path,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $this->serializer->serialize($data, self::REQUEST_FORMAT),
        );
        return $this->client->getResponse();
    }
}
