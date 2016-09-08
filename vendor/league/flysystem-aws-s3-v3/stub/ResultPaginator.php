<?php

namespace League\Flysystem\AwsS3v3\Stub;

use Aws\Result;
use GuzzleHttp\Promise;

class ResultPaginator
{

    /**
     * @var Result
     */
    private $result;

    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * @param callable $callback
     *
     * @return Promise
     */
    public function each(callable $callback)
    {
        return Promise\promise_for($callback($this->result));
    }
}