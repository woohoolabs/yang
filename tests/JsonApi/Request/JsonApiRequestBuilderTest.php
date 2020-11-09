<?php

declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Request;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Request\JsonApiRequestBuilder;
use WoohooLabs\Yang\JsonApi\Request\ResourceObject;

use function urldecode;

class JsonApiRequestBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function fetch(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->fetch();

        $this->assertSame("GET", $requestBuilder->getRequest()->getMethod());
    }

    /**
     * @test
     */
    public function create(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->create();

        $this->assertSame("POST", $requestBuilder->getRequest()->getMethod());
    }

    /**
     * @test
     */
    public function update(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->update();

        $this->assertSame("PATCH", $requestBuilder->getRequest()->getMethod());
    }

    /**
     * @test
     */
    public function delete(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->delete();

        $this->assertSame("DELETE", $requestBuilder->getRequest()->getMethod());
    }

    /**
     * @test
     */
    public function setProtocolVersion(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setProtocolVersion("2.0");

        $this->assertSame("2.0", $requestBuilder->getRequest()->getProtocolVersion());
    }

    /**
     * @test
     */
    public function http(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->http();

        $this->assertSame("http", $requestBuilder->getRequest()->getUri()->getScheme());
    }

    /**
     * @test
     */
    public function https(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->https();

        $this->assertSame("https", $requestBuilder->getRequest()->getUri()->getScheme());
    }

    /**
     * @test
     */
    public function setUriWhenInvalid(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUri("host:0");

        $this->assertSame("http://localhost", $requestBuilder->getRequest()->getUri()->__toString());
    }

    /**
     * @test
     */
    public function setUri(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUri("https://example.com/api/users");

        $this->assertSame("https://example.com/api/users", $requestBuilder->getRequest()->getUri()->__toString());
    }

    /**
     * @test
     */
    public function setUriWithoutPath(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUri("https://example.com");

        $this->assertSame("https://example.com", $requestBuilder->getRequest()->getUri()->__toString());
    }

    /**
     * @test
     */
    public function setUriWithPort(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUri("https://example.com:8000");

        $this->assertSame("https://example.com:8000", $requestBuilder->getRequest()->getUri()->__toString());
    }

    /**
     * @test
     */
    public function setUriWithQueryParamAsZero(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUri("https://example.com/api/users?0");

        $this->assertSame("https://example.com/api/users?0=", $requestBuilder->getRequest()->getUri()->__toString());
    }

    /**
     * @test
     */
    public function setUriHost(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUriHost("example.com");

        $this->assertSame("example.com", $requestBuilder->getRequest()->getUri()->getHost());
    }

    /**
     * @test
     */
    public function setUriPort(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUriPort(8080);

        $this->assertSame(8080, $requestBuilder->getRequest()->getUri()->getPort());
    }

    /**
     * @test
     */
    public function setUriPath(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUriPath("/api/users");

        $this->assertSame("/api/users", $requestBuilder->getRequest()->getUri()->getPath());
    }

    /**
     * @test
     */
    public function setUriQueryParamWithZeroValue(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setUriQueryParam("a", "0");

        $this->assertSame("a=0", $requestBuilder->getRequest()->getUri()->getQuery());
    }

    /**
     * @test
     */
    public function setHeader(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setHeader("a", "b");

        $this->assertSame(["b"], $requestBuilder->getRequest()->getHeader("a"));
    }

    /**
     * @test
     */
    public function setJsonApiFields(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiFields(["a" => ["b", "c"]]);

        $this->assertSame("fields[a]=b,c", urldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function setJsonApiSort(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiSort(["b", "-c"]);

        $this->assertSame("sort=b,-c", urldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function setJsonApiPage(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiPage(["a" => 1, "b" => 100]);

        $this->assertSame("page[a]=1&page[b]=100", urldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function setJsonApiFilter(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiFilter(["a" => "abc"]);

        $this->assertSame("filter[a]=abc", urldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function setJsonApiFilterWithArrayValue(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiFilter(["a" => ["abc", "xyz"]]);

        $this->assertSame(
            "filter[a]=abc,xyz",
            urldecode($requestBuilder->getRequest()->getUri()->getQuery())
        );
    }

    /**
     * @test
     */
    public function setJsonApiFilterRawWithArrayValue(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiFilterRaw(["a" => ["abc", "xyz"]]);

        $this->assertSame(
            "filter[a][0]=abc&filter[a][1]=xyz",
            urldecode($requestBuilder->getRequest()->getUri()->getQuery())
        );
    }

    /**
     * @test
     */
    public function setJsonApiFilterRawWithNestedArrayValue(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiFilterRaw(
            [
                "a" => 123,
                "b" => ['lt' => 50, 'gt' => 5],
                "or" => ["c" => ["like" => "foo", "eq" => "bar"]],
                "d" => ["in" => [3, 4]],
            ]
        );

        $this->assertSame(
            "filter[a]=123&filter[b][lt]=50&filter[b][gt]=5&filter[or][c][like]=foo"
            . "&filter[or][c][eq]=bar&filter[d][in][0]=3&filter[d][in][1]=4",
            urldecode($requestBuilder->getRequest()->getUri()->getQuery())
        );
    }

    /**
     * @test
     */
    public function setJsonApiIncludesWhenArray(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiIncludes(["a", "b.c"]);

        $this->assertSame("include=a,b.c", urldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function setJsonApiIncludesWhenString(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiIncludes("a,b.c");

        $this->assertSame("include=a,b.c", urldecode($requestBuilder->getRequest()->getUri()->getQuery()));
    }

    /**
     * @test
     */
    public function addJsonApiAppliedProfile(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder
            ->addJsonApiAppliedProfile("abc")
            ->addJsonApiAppliedProfile("def");

        $this->assertSame(
            'application/vnd.api+json;profile="abc def"',
            $requestBuilder->getRequest()->getHeaderLine("content-type")
        );
    }

    /**
     * @test
     */
    public function addJsonApiRequestedProfile(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder
            ->addJsonApiRequestedProfile("abc")
            ->addJsonApiRequestedProfile("def");

        $this->assertSame(
            'application/vnd.api+json;profile="abc def",application/vnd.api+json',
            $requestBuilder->getRequest()->getHeaderLine("accept")
        );
    }

    /**
     * @test
     */
    public function addJsonApiRequiredProfile(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder
            ->addJsonApiRequiredProfile("abc")
            ->addJsonApiRequiredProfile("def");

        $this->assertSame(
            'profile=abc def',
            urldecode($requestBuilder->getRequest()->getUri()->getQuery())
        );
    }

    /**
     * @test
     */
    public function setBodyWhenResourceObject(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiBody(new ResourceObject("", ""));

        $this->assertSame('{"data":{"type":""}}', $requestBuilder->getRequest()->getBody()->__toString());
    }

    /**
     * @test
     */
    public function setBodyWhenArray(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $requestBuilder->setJsonApiBody([]);

        $this->assertSame("[]", $requestBuilder->getRequest()->getBody()->__toString());
    }

    /**
     * @test
     */
    public function getRequestWithCorrectAcceptHeader(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $accept = $requestBuilder->getRequest()->getHeader("accept");

        $this->assertSame(["application/vnd.api+json"], $accept);
    }

    /**
     * @test
     */
    public function getRequestWithCorrectContentTypeHeader(): void
    {
        $requestBuilder = $this->createRequestBuilder();

        $contentType = $requestBuilder->getRequest()->getHeader("content-type");

        $this->assertSame(["application/vnd.api+json"], $contentType);
    }

    private function createRequestBuilder(): JsonApiRequestBuilder
    {
        return new JsonApiRequestBuilder(new Request("GET", ""));
    }
}
