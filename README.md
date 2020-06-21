[![Build Status](https://travis-ci.com/php-task-runner/sparql-robo-tasks.svg?branch=master)](https://travis-ci.com/php-task-runner/sparql-robo-tasks)

This repository provides SPARQL tasks for [Robo](
https://github.com/consolidation/Robo/).

## Tasks

### Query

```php
$query1 = 'SELECT ?s ?p ?o WHERE { ?s ?p ?o } LIMIT 100';
$query2 = '...';

$result = $this->taskSparqlQuery()
    ->setEndpointUrl('http://example.com/sparql')
    ->addQuery($query1)
    ->addQuery($query2)
    ->run();

// Result of $query1.
$res1 = $result->getData()['result'][$query1];
// Result of $query2.
$res2 = $result->getData()['result'][$query2];
```

### Import triples from strings

```php
$triples1 = <<<TRIPLES
<?xml version="1.0"?>
<rdf:RDF
xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
xmlns:si="https://www.w3schools.com/rdf/">
<rdf:Description rdf:about="https://www.w3schools.com">
  <si:title>W3Schools</si:title>
  <si:author>Jan Egil Refsnes</si:author>
</rdf:Description>
</rdf:RDF>
TRIPLES;
$triples2 = <<<TRIPLES
<?xml version="1.0"?>
<RDF>
  <Description about="https://www.w3schools.com/rdf">
    <author>Jan Egil Refsnes</author>
    <homepage>https://www.w3schools.com</homepage>
  </Description>
</RDF>
TRIPLES;

$this->taskSparqlImportFromString()
    ->setEndpointUrl('http://example.com/sparql-graph-crud')
    ->addTriples('http://example.com/graph1', $triples1)
    ->addTriples('http://example.com/graph2', $triples2)
    ->run();
```

### Import triples from files

```php
$this->taskSparqlImportFromFile()
    ->setEndpointUrl('http://example.com/sparql-graph-crud')
    ->addTriples('http://example.com/graph1', '/path/to/file.rdf')
    ->addTriples('http://example.com/graph2', '/other/path/to/file2.rdf')
    ->run();
```
