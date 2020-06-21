<?php

declare(strict_types=1);

namespace Robo\Sparql\Tasks\Sparql;

/**
 * Provides task allowing to import triples from files in a SPARQL storage.
 */
class ImportFromFile extends AbstractImport
{
    /**
     * {@inheritdoc}
     */
    protected function buildTriplesBlob(string $graphUri): string
    {
        return file_get_contents($this->stack[$graphUri]);
    }
}
