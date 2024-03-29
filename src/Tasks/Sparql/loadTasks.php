<?php

declare(strict_types=1);

namespace Robo\Sparql\Tasks\Sparql;

use Robo\Collection\CollectionBuilder;

/**
 * Provides loaders for SPARQL Robo tasks.
 */
trait loadTasks
{
    /**
     * Provides a tasks loader for Sparql/Query task.
     *
     * @return \Robo\Sparql\Tasks\Sparql\Query|\Robo\Collection\CollectionBuilder
     *   The task object.
     */
    public function taskSparqlQuery(): CollectionBuilder
    {
        return $this->task(Query::class);
    }

    /**
     * Provides a tasks loader for Sparql/Import task.
     *
     * @return \Robo\Sparql\Tasks\Sparql\ImportFromString|\Robo\Collection\CollectionBuilder
     *   The task object.
     */
    public function taskSparqlImportFromString(): CollectionBuilder
    {
        return $this->task(ImportFromString::class);
    }

    /**
     * Provides a tasks loader for Sparql/ImportFromFile task.
     *
     * @return \Robo\Sparql\Tasks\Sparql\ImportFromFile|\Robo\Collection\CollectionBuilder
     *   The task object.
     */
    public function taskSparqlImportFromFile()
    {
        return $this->task(ImportFromFile::class);
    }
}
