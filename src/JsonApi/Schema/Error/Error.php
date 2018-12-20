<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Error;

use WoohooLabs\Yang\JsonApi\Schema\Link\ErrorLinks;
use function is_array;
use function is_scalar;
use function is_string;

final class Error
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var array
     */
    private $meta;

    /**
     * @var ErrorLinks
     */
    private $links;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $detail;

    /**
     * @var ErrorSource
     */
    private $source;

    public function __construct(
        string $id,
        array $meta,
        ErrorLinks $links,
        string $status,
        string $code,
        string $title,
        string $detail,
        ErrorSource $source
    ) {
        $this->id = $id;
        $this->meta = $meta;
        $this->links = $links;
        $this->status = $status;
        $this->code = $code;
        $this->title = $title;
        $this->detail = $detail;
        $this->source = $source;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function hasMeta(): bool
    {
        return empty($this->meta) === false;
    }

    public function meta(): array
    {
        return $this->meta;
    }

    public function hasLinks(): bool
    {
        return $this->links->hasAnyLinks();
    }

    public function links(): ErrorLinks
    {
        return $this->links;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function detail(): string
    {
        return $this->detail;
    }

    public function hasSource(): bool
    {
        return $this->source->hasSource();
    }

    public function source(): ErrorSource
    {
        return $this->source;
    }

    /**
     * @internal
     */
    public static function fromArray(array $error): Error
    {
        $id = isset($error["id"]) && is_string($error["id"]) ? $error["id"] : "";
        $meta = isset($error["meta"]) && is_array($error["meta"]) ? $error["meta"] : [];
        $links = ErrorLinks::fromArray(isset($error["links"]) && is_array($error["links"]) ? $error["links"] : []);
        $status = isset($error["status"]) && is_scalar($error["status"]) ? (string) $error["status"] : "";
        $code = isset($error["code"]) && is_string($error["code"]) ? $error["code"] : "";
        $title = isset($error["title"]) && is_string($error["title"]) ? $error["title"] : "";
        $detail = isset($error["detail"]) && is_string($error["detail"]) ? $error["detail"] : "";
        $source = ErrorSource::fromArray(isset($error["source"]) && is_array($error["source"]) ? $error["source"] : []);

        return new self($id, $meta, $links, $status, $code, $title, $detail, $source);
    }

    public function toArray(): array
    {
        $content = [];

        if ($this->id !== "") {
            $content["id"] = $this->id;
        }

        if (empty($this->meta) === false) {
            $content["meta"] = $this->meta;
        }

        if ($this->hasLinks()) {
            $content["links"] = $this->links->toArray();
        }

        if ($this->status !== "") {
            $content["status"] = $this->status;
        }

        if ($this->code !== "") {
            $content["code"] = $this->code;
        }

        if ($this->title !== "") {
            $content["title"] = $this->title;
        }

        if ($this->detail !== "") {
            $content["detail"] = $this->detail;
        }

        if ($this->hasSource()) {
            $content["source"] = $this->source->toArray();
        }

        return $content;
    }
}
