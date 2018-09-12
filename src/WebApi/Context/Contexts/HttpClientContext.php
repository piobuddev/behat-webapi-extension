<?php declare(strict_types=1);

namespace WebApi\Context\Contexts;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\RequestOptions;
use PHPUnit\Framework\Assert;
use RepositoryTester\Repository\Connection\ConnectionInterface;
use RepositoryTester\RepositoryAssertTrait;
use WebApi\Context\HttpClientAwareContextInterface;
use WebApi\Context\RepositoryAwareContextInterface;
use WebApi\HttpClient\HttpClientInterface;

class HttpClientContext implements HttpClientAwareContextInterface, RepositoryAwareContextInterface
{
    use RepositoryAssertTrait;

    /**
     * @var \WebApi\HttpClient\HttpClientInterface
     */
    private $httpClient;

    /**
     * Http client default options
     *
     * @var array
     */
    protected $options = [];

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $response;

    /**
     * @var \RepositoryTester\Repository\Connection\ConnectionInterface
     */
    private $connection;

    /**
     * @param \WebApi\HttpClient\HttpClientInterface $httpClient
     */
    public function setClient(HttpClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param \RepositoryTester\Repository\Connection\ConnectionInterface $connection
     */
    public function setConnection(ConnectionInterface $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)"$/
     *
     * @param string $method
     * @param string $url
     */
    public function iSendARequest(string $method, string $url)
    {
        list($url, $queryString) = array_pad(explode('?', $url), 2, null);
        if ($queryString) {
            parse_str($queryString, $this->options[RequestOptions::QUERY]);
        }

        $this->response = $this->httpClient->request($method, $url, $this->options);
    }

    /**
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)":$/
     *
     * @param string $method
     * @param string $url
     */
    public function iSend(string $method, string $url, TableNode $data)
    {
        $this->options[RequestOptions::JSON] = $data->getHash()[0];

        $this->response = $this->httpClient->request($method, $url, $this->options);
    }


    /**
     * @Then /^(?:the )?response code should be (\d+)$/
     *
     * @param string $code
     */
    public function theResponseCodeShouldBe(string $code)
    {
        Assert::assertEquals((int)$code, $this->response->getStatusCode());
    }

    /**
     * @Then /^(?:the )JSON response should contain:$/
     *
     * @param \Behat\Gherkin\Node\TableNode $expectedTable
     */
    public function theResponseShouldContainJson(TableNode $expectedTable)
    {
        $body = (string)$this->response->getBody();

        Assert::assertTrue($this->isJson($body), 'Response is not in JSON format');

        $expected = $this->parseValues($expectedTable->getHash());
        $expected = count($expected) === 1 ? array_pop($expected) : $expected;
        $response = json_decode($body, true);

        $actual = isset($response['data']) ? $response['data'] : $response;

        Assert::assertEquals($expected, $actual);
    }

    /**
     * @Then /^(?:the )JSON response should be equal:$/
     */
    public function theResponseShouldBeEqual(PyStringNode $expectedResponse)
    {
        $body = (string)$this->response->getBody();

        Assert::assertTrue($this->isJson($body), 'Response is not in JSON format');
        Assert::assertEquals($expectedResponse, $body);
    }

    /**
     * @Then /^(?:the )?response should return (\d+) results$/
     * @param string $amount
     */
    public function theResponseShouldReturnResults(string $amount)
    {
        Assert::assertCount((int)$amount, json_decode((string)$this->response->getBody()));
    }

    /**
     * @Then /^(?:the )?response should return existing (\w+)s$/
     *
     * @param string $table
     */
    public function responseShouldReturnExisting(string $table)
    {
        $response = json_decode((string)$this->response->getBody(), true);
        //todo: refactor
        foreach ($response as &$row) {
            foreach ($row as $key => $value) {
                $newKey = preg_replace_callback(
                    "#(?!^)[A-Z](?=[a-z]+)#",
                    function (array $matches) {
                        return '_' . strtolower(array_pop($matches));
                    },
                    $key
                );
                unset($row[$key]);
                $row[$newKey] = $value;
            }
        }
        $this->assertRepositoryHasRows($table, $response);
    }

    /**
     * @param string $string
     *
     * @return bool
     */
    private function isJson(string $string): bool
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function parseValues(array $data): array
    {
        array_walk_recursive(
            $data,
            function (&$row) {
                $row = ($row === 'null') ? null : $row;
            }
        );

        return $data;
    }
}
