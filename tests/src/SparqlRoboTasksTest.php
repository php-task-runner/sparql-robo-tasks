<?php

declare(strict_types=1);

namespace Robo\Sparql\Tests;

use League\Container\ContainerAwareTrait;
use PHPUnit\Framework\TestCase;
use Robo\Collection\CollectionBuilder;
use Robo\Robo;
use Robo\Sparql\Tasks\Sparql\loadTasks;
use Robo\TaskAccessor;
use Symfony\Component\Console\Output\NullOutput;

class SparqlRoboTasksTest extends TestCase
{
    use loadTasks;
    use ContainerAwareTrait;
    use TaskAccessor;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $container = Robo::createDefaultContainer(null, new NullOutput());
        $this->setContainer($container);
    }

    /**
     * Scaffold the collection builder.
     *
     * @return \Robo\Collection\CollectionBuilder
     */
    public function collectionBuilder(): CollectionBuilder
    {
        $emptyRobofile = new \Robo\Tasks();
        return $this->getContainer()->get('collectionBuilder', [$emptyRobofile]);
    }

    /**
     * @covers \Robo\Sparql\Tasks\Sparql\Query
     */
    public function testQuery(): void
    {
        $query1 = 'INSERT INTO <http://example.com/graph>  { <http://example.com/subject> <http://example.com/predicate> "test" . }';
        $query2 = 'SELECT ?subject ?predicate ?object WHERE { GRAPH <http://example.com/graph> { ?subject ?predicate ?object } }';
        $query3 = 'CLEAR GRAPH <http://example.com/graph>';
        $result = $this->taskSparqlQuery()
            ->setEndpointUrl($this->getSparqlEndpoint() . '/sparql')
            ->addQuery($query1)
            ->addQuery($query2)
            ->addQuery($query3)
            ->run();

        /** @var \EasyRdf\Sparql\Result[] $results */
        $results = $result->getData()['results'];

        $this->assertSame('http://example.com/subject', $results[1][0]->subject->getUri());
        $this->assertSame('http://example.com/predicate', $results[1][0]->predicate->getUri());
        $this->assertSame('test', $results[1][0]->object->getValue());
    }

    /**
     * @covers       \Robo\Sparql\Tasks\Sparql\ImportFromString
     * @dataProvider importDataProvider
     *
     * @param string $method
     * @param string $triples1
     * @param string $triples2
     */
    public function testImport(string $method, string $triples1, string $triples2): void
    {
        $this->{$method}()
            ->setEndpointUrl($this->getSparqlEndpoint() . '/sparql-graph-crud')
            ->addTriples('http://example.com/graph1', $triples1)
            ->addTriples('http://example.com/graph2', $triples2)
            ->run();

        $result = $this->taskSparqlQuery()
          ->setEndpointUrl($this->getSparqlEndpoint() . '/sparql')
          ->addQuery('SELECT ?subject ?predicate ?object WHERE { GRAPH <http://example.com/graph1> { ?subject ?predicate ?object } }')
          ->addQuery('SELECT ?subject ?predicate ?object WHERE { GRAPH <http://example.com/graph2> { ?subject ?predicate ?object } }')
          ->addQuery('CLEAR GRAPH <http://example.com/graph1>')
          ->addQuery('CLEAR GRAPH <http://example.com/graph2>')
          ->run();

        /** @var \EasyRdf\Sparql\Result[] $results */
        $results = $result->getData()['results'];

        $this->assertSame('http://example.com/subject1', $results[0][0]->subject->getUri());
        $this->assertSame('http://example.com/predicate', $results[0][0]->predicate->getUri());
        $this->assertSame('test 1', $results[0][0]->object->getValue());
        $this->assertSame('http://example.com/subject2', $results[1][0]->subject->getUri());
        $this->assertSame('http://example.com/predicate', $results[1][0]->predicate->getUri());
        $this->assertSame('test 2', $results[1][0]->object->getValue());
    }

    /**
     * @see self::testImport()
     * @return array
     */
    public function importDataProvider(): array
    {
        return [
          'import from string' => [
            'taskSparqlImportFromString',
            file_get_contents(__DIR__ . '/../fixtures/triples1.rdf'),
            file_get_contents(__DIR__ . '/../fixtures/triples2.rdf'),
          ],
          'import from file' => [
            'taskSparqlImportFromFile',
            __DIR__ . '/../fixtures/triples1.rdf',
            __DIR__ . '/../fixtures/triples2.rdf',
          ],
        ];
    }

    /**
     * @return string
     */
    protected function getSparqlEndpoint(): string
    {
        return getenv('SPARQL_ENDPOINT') ?: 'http://dba:dba@localhost:8890';
    }
}
