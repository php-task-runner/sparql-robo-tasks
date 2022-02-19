<?php

declare(strict_types=1);

namespace Robo\Sparql\Tasks\Sparql;

use EasyRdf\Graph;
use EasyRdf\GraphStore;
use Robo\Result;
use Robo\Task\BaseTask;

abstract class AbstractImport extends BaseTask
{
    use SparqlTrait;

    /**
     * A stack of items to be imported.
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
            return Result::error($this, 'Endpoint URL not set.');
        }
        if (!$this->stack) {
            return Result::error($this, 'No import content was passed.');
        }

        $graphStore = new GraphStore($this->endpointUrl);
        $data = [];
        foreach (array_keys($this->stack) as $graphUri) {
            $this->printTaskInfo("Importing triples in '{uri}' graph", ['uri' => $graphUri]);
            try {
                $graph = new Graph($graphUri, $this->buildTriplesBlob($graphUri));
                $graphStore->replace($graph);
            } catch (\Throwable $exception) {
                $data['original_exception'] = $exception;
                return Result::error($this, "Triples import failed with: '{$exception->getMessage()}'", $data);
            }
        }

        // Cleanup the stack.
        $this->stack = [];

        return Result::success($this, "Imported triples in '{uris}' graph", ['uris' => implode("', '", array_keys($this->stack))]);
    }

    /**
     * Builds a blob of triples from a stack entry.
     *
     * @param string $graphUri
     *   The URI of the graph where to store the triples.
     *
     * @return string
     *   A blob of triples.
     */
    abstract protected function buildTriplesBlob(string $graphUri): string;

    /**
     * Adds a new triples to be imported.
     *
     * @param string $graphUri
     *   The URI of the graph where to store the triples.
     * @param mixed $content
     *   Content to be imported. This parameter is intentionally not typed as
     *   classes might define their own logic. Some implementations will use
     *   this parameter to import triples from string blobs, of file names,
     *   arrays of triples, and so on.
     *
     * @return $this
     */
    public function addTriples(string $graphUri, $content): self
    {
        $this->stack[$graphUri] = $content;
        return $this;
    }
}
