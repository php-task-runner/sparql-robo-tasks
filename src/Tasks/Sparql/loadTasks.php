<?php

declare(strict_types=1);

namespace Robo\Sparql\Tasks\Sparql;

/**
 * Provides loaders for SPARQL Robo tasks.
 */
trait loadTasks
{
    /**
     * Provides a tasks loader for Sparql/Query task.
     *
     * @return \Joinup\Tasks\Sparql\Query|\Robo\Collection\CollectionBuilder
     *   The task object.
     */
    public function taskSparqlQuery()
    {
        return $this->task(Query::class);
    }

    /**
     * Provides a tasks loader for Sparql/Import task.
     *
     * @return \Joinup\Tasks\Sparql\ImportFromString|\Robo\Collection\CollectionBuilder
     *   The task object.
     */
    public function taskSparqlImportFromString()
    {
        return $this->task(ImportFromString::class);
    }

    /**
     * Provides a tasks loader for Sparql/ImportFromFile task.
     *
     * @return \Joinup\Tasks\Sparql\ImportFromFile|\Robo\Collection\CollectionBuilder
     *   The task object.
     */
    public function taskSparqlImportFromFile()
    {
        return $this->task(ImportFromFile::class);
    }
}
