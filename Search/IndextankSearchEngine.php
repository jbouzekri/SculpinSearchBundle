<?php

namespace Jb\Bundle\SearchBundle\Search;

/**
 * Indextank search engine
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class IndextankSearchEngine implements SearchEngineInterface
{
    /**
     * @var \Indextank_Api
     */
    private $client;

    /**
     * @var string
     */
    private $indexName;

    /**
     * @var \Indextank_Index
     */
    private $index;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * Constructor
     *
     * @param \Indextank_Api $client
     * @param string $index
     * @param string $user
     * @param string $password
     */
    public function __construct(\Indextank_Api $client, $index, $user, $password)
    {
        $this->client = $client;
        $this->indexName = $index;
        $this->index = $client->get_index($this->indexName);
        $this->user = $user;
        $this->password = $password;
    }
    /**
     * {@inheritDoc}
     */
    public function bulkAdd(array $documents)
    {
        return $this->client->api_call(
            'PUT',
            $this->client->index_url($this->indexName). "/docs",
            $documents,
            $this->getHeaders()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function synchronize(array $documents)
    {
        $this->clearIndex();

        return $this->bulkAdd($documents);
    }

    /**
     * Clear the index
     *
     * @return \Indextank_Response
     */
    private function clearIndex()
    {
        return $this->client->api_call(
            'DELETE',
            $this->client->index_url($this->indexName).'/search',
            array("q" => "type:document"),
            $this->getHeaders()
        );
    }

    /**
     * Get authorization headers for curl call
     *
     * @return array
     */
    private function getHeaders()
    {
        return array(
            CURLOPT_USERPWD => $this->user.':'.$this->password
        );
    }
}
