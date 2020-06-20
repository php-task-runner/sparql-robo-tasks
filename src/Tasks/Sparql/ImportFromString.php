<?php

declare(strict_types=1);

namespace Robo\Sparql\Tasks\Sparql;

/**
 * Provides task allowing to import triples in a SPARQL storage.
 */
class ImportFromString extends AbstractImport
{
    /**
     * {@inheritdoc}
     */
    protected function buildTriplesBlob(string $graphUri): string
    {
        return implode("\n", $this->stack[$graphUri]);
    }
}
