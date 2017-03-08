<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Request;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Request\JsonApiRequestBuilder;

class JsonApiRequestBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function fetch()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->fetch();

        $this->assertEquals("GET", $requestBuilder->getRequest()->getMethod());
    }

    /**
     * @test
     */
    public function create()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->create();

        $this->assertEquals("POST", $requestBuilder->getRequest()->getMethod());
    }

    /**
     * @test
     */
    public function update()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->update();

        $this->assertEquals("PATCH", $requestBuilder->getRequest()->getMethod());
    }

    /**
     * @test
     */
    public function delete()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->delete();

        $this->assertEquals("DELETE", $requestBuilder->getRequest()->getMethod());
    }

    /**
     * @test
     */
    public function setProtocolVersion()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setProtocolVersion("2.0");

        $this->assertEquals("2.0", $requestBuilder->getRequest()->getProtocolVersion());
    }

    /**
     * @test
     */
    public function http()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->http();

        $this->assertEquals("http", $requestBuilder->getRequest()->getUri()->getScheme());
    }

    /**
     * @test
     */
    public function https()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->https();

        $this->assertEquals("https", $requestBuilder->getRequest()->getUri()->getScheme());
    }

    /**
     * @test
     */
    public function setUri()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUri("http://example.com/api/users");

        $this->assertEquals("http://example.com/api/users", $requestBuilder->getRequest()->getUri()->__toString());
    }

    /**
     * @test
     */
    public function setUriHost()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUriHost("example.com");

        $this->assertEquals("example.com", $requestBuilder->getRequest()->getUri()->getHost());
    }

    /**
     * @test
     */
    public function setUriPort()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUriPort(8080);

        $this->assertEquals(8080, $requestBuilder->getRequest()->getUri()->getPort());
    }

    /**
     * @test
     */
    public function setUriPath()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUriPath("/api/users");

        $this->assertEquals("/api/users", $requestBuilder->getRequest()->getUri()->getPath());
    }

    /**
     * @test
     */
    public function setUriQueryParam()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUriQueryParam("a", "b");

        $this->assertEquals("a=b", $requestBuilder->getRequest()->getUri()->getQuery());
    }

    /**
     * @test
     */
    public function setHeader()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setHeader("a", "b");

        $this->assertEquals(["b"], $requestBuilder->getRequest()->getHeader("a"));
    }

    /**
     * @test
     */
    public function setJsonApiFields()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiFields(["a" => ["b", "c"]]);

        $this->assertEquals("fields[a]=b,c", rawurldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function setJsonApiSort()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiSort(["b", "-c"]);

        $this->assertEquals("sort=b,-c", rawurldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function setJsonApiPage()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiPage(["a" => 1, "b" => 100]);

        $this->assertEquals("page[a]=1&page[b]=100", rawurldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function setJsonApiFilter()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiFilter(["a" => "abc"]);

        $this->assertEquals("filter[a]=abc", rawurldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function setJsonApiIncludes()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiIncludes(["a", "b.c"]);

        $this->assertEquals("include=a,b.c", rawurldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function getRequestWithCorrectAcceptHeader()
    {
        $requestBuilder = $this->createRequestBuilder();

        $this->assertEquals(["application/vnd.api+json"], $requestBuilder->getRequest()->getHeader("Accept"));
    }

    /**
     * @test
     */
    public function getRequestWithCorrectContentTypeHeader()
    {
        $requestBuilder = $this->createRequestBuilder();

        $this->assertEquals(["application/vnd.api+json"], $requestBuilder->getRequest()->getHeader("Content-Type"));
    }

    private function createRequestBuilder(): JsonApiRequestBuilder
    {
        return new JsonApiRequestBuilder(new Request("", ""));
    }
}
