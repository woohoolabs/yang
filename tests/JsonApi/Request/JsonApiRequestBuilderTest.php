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

        $this->assertSame("GET", $requestBuilder->getRequest()->getMethod());
    }

    /**
     * @test
     */
    public function create()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->create();

        $this->assertSame("POST", $requestBuilder->getRequest()->getMethod());
    }

    /**
     * @test
     */
    public function update()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->update();

        $this->assertSame("PATCH", $requestBuilder->getRequest()->getMethod());
    }

    /**
     * @test
     */
    public function delete()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->delete();

        $this->assertSame("DELETE", $requestBuilder->getRequest()->getMethod());
    }

    /**
     * @test
     */
    public function setProtocolVersion()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setProtocolVersion("2.0");

        $this->assertSame("2.0", $requestBuilder->getRequest()->getProtocolVersion());
    }

    /**
     * @test
     */
    public function http()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->http();

        $this->assertSame("http", $requestBuilder->getRequest()->getUri()->getScheme());
    }

    /**
     * @test
     */
    public function https()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->https();

        $this->assertSame("https", $requestBuilder->getRequest()->getUri()->getScheme());
    }

    /**
     * @test
     */
    public function setUri()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUri("http://example.com/api/users");

        $this->assertSame("http://example.com/api/users", $requestBuilder->getRequest()->getUri()->__toString());
    }

    /**
     * @test
     */
    public function setUriWithoutPath()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUri("http://example.com");

        $this->assertSame("http://example.com", $requestBuilder->getRequest()->getUri()->__toString());
    }

    /**
     * @test
     */
    public function setUriWithQueryParamAsZero()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUri("http://example.com/api/users?0");

        $this->assertSame("http://example.com/api/users?0=", $requestBuilder->getRequest()->getUri()->__toString());
    }

    /**
     * @test
     */
    public function setUriHost()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUriHost("example.com");

        $this->assertSame("example.com", $requestBuilder->getRequest()->getUri()->getHost());
    }

    /**
     * @test
     */
    public function setUriPort()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUriPort(8080);

        $this->assertSame(8080, $requestBuilder->getRequest()->getUri()->getPort());
    }

    /**
     * @test
     */
    public function setUriPath()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUriPath("/api/users");

        $this->assertSame("/api/users", $requestBuilder->getRequest()->getUri()->getPath());
    }

    /**
     * @test
     */
    public function setUriQueryParamWithZeroValue()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUriQueryParam("a", "0");

        $this->assertSame("a=0", $requestBuilder->getRequest()->getUri()->getQuery());
    }

    /**
     * @test
     */
    public function setHeader()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setHeader("a", "b");

        $this->assertSame(["b"], $requestBuilder->getRequest()->getHeader("a"));
    }

    /**
     * @test
     */
    public function setJsonApiFields()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiFields(["a" => ["b", "c"]]);

        $this->assertSame("fields[a]=b,c", rawurldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function setJsonApiSort()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiSort(["b", "-c"]);

        $this->assertSame("sort=b,-c", rawurldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function setJsonApiPage()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiPage(["a" => 1, "b" => 100]);

        $this->assertSame("page[a]=1&page[b]=100", rawurldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function setJsonApiFilter()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiFilter(["a" => "abc"]);

        $this->assertSame("filter[a]=abc", rawurldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function setJsonApiIncludes()
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiIncludes(["a", "b.c"]);

        $this->assertSame("include=a,b.c", rawurldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function getRequestWithCorrectAcceptHeader()
    {
        $requestBuilder = $this->createRequestBuilder();

        $this->assertSame(["application/vnd.api+json"], $requestBuilder->getRequest()->getHeader("Accept"));
    }

    /**
     * @test
     */
    public function getRequestWithCorrectContentTypeHeader()
    {
        $requestBuilder = $this->createRequestBuilder();

        $this->assertSame(["application/vnd.api+json"], $requestBuilder->getRequest()->getHeader("Content-Type"));
    }

    private function createRequestBuilder(): JsonApiRequestBuilder
    {
        return new JsonApiRequestBuilder(new Request("", ""));
    }
}
