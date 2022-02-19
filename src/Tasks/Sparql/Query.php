<?php

declare(strict_types=1);

namespace Robo\Sparql\Tasks\Sparql;

use EasyRdf\Sparql\Client;
use Robo\Result;
use Robo\Task\BaseTask;

/**
 * Provides a SPARQL query task.
 */
class Query extends BaseTask
{
    use SparqlTrait;

    /**
     * Storage for the list of queries.
     *
     * @var array
     */
    protected array $stack = [];

    /**
     * {@inheritdoc}
     */
    public function run(): Result
    {
        if (empty($this->endpointUrl)) {
            return Result::error($this, "Endpoint URL not set");
        }
        if (!$this->stack) {
            return Result::error($this, 'No queries were passed.');
        }

        $client = new Client($this->endpointUrl);
        $data = ['results' => []];
        foreach ($this->stack as $query) {
            $this->printTaskInfo("Querying SPARQL: '{query}'", ['query' => $query]);
            try {
                $data['results'][] = $client->query($query);
            } catch (\Throwable $exception) {
                $data['original_exception'] = $exception;
                return Result::error($this, "Query failed with: '{$exception->getMessage()}'", $data);
            }
        }

        // Cleanup the stack.
        $this->stack = [];

        return Result::success($this, 'Queries ran successfully', $data);
    }

    /**
     * Adds a new query.
     *
     * @param string $query
     *   The query to run against the backend.
     *
     * @return $this
     */
    public function addQuery(string $query): self
    {
        $this->stack[] = $query;
        return $this;
    }
}
