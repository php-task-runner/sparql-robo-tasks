<?php

declare(strict_types=1);

namespace Robo\Sparql\Tasks\Sparql;

/**
 * Reusable code for SPARQL Robo tasks
 */
trait SparqlTrait
{
    /**
     * The SPARQL endpoint URL.
     *
     * @var string
     */
    protected $endpointUrl;

    /**
     * Sets the SPARQL endpoint.
     *
     * @param string $endpointUrl
     *   The URL of the SPARQL endpoint.
     *
     * @return $this
     */
    public function setEndpointUrl(string $endpointUrl): self
    {
        $this->endpointUrl = $endpointUrl;
        return $this;
    }
}
