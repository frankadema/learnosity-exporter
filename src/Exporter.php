<?php

namespace App;

/**
 * Class Exporter
 */
class Exporter
{
    /**
     * @var Requester
     */
    private $requester;

    /**
     * Exporter constructor.
     * @param Requester $requester
     */
    public function __construct(
        Requester $requester
    ) {
        $this->requester = $requester;
    }

    /**
     * @param string      $dataDir
     * @param string|null $next
     *
     * @return array
     */
    public function exportItems(string $dataDir, string $next = null)
    {
        $body = $this->requester->get('https://data.learnosity.com/v1/itembank/items', $next);

        $count = 0;

        foreach ($body['data'] as $item) {
            $file = $dataDir.'/'.$item['reference'].'.json';

            file_put_contents($file, json_encode($item));

            ++$count;
        }

        return [$this->getNext($body), $count];
    }

    /**
     * @param string      $dataDir
     * @param string|null $next
     *
     * @return array
     */
    public function exportQuestions(string $dataDir, string $next = null)
    {
        $body = $this->requester->get('https://data.learnosity.com/v1/itembank/questions', $next);

        $count = 0;

          foreach ($body['data'] as $item){
            if(! glob($dataDir.'/'.$item['type'])){
              mkdir($dataDir.'/'.$item['type']);
            }

            $file = $dataDir.'/'.$item['type'].'/'.$item['reference'].'.json';

            file_put_contents($file, json_encode($item));

            ++$count;
          }
        return [$this->getNext($body), $count];
    }
    /**
     * @param string      $dataDir
     * @param string|null $next
     *
     * @return array
     */
    public function exportActivities(string $dataDir, string $next = null)
    {
        $body = $this->requester->get('https://data.learnosity.com/v1/itembank/activities', $next);

        $count = 0;

        foreach ($body['data'] as $item) {
            $file = $dataDir.'/'.$item['reference'].'.json';

            file_put_contents($file, json_encode($item));

            ++$count;
        }

        return [$this->getNext($body), $count];
    }

    /**
     * @param string      $dataDir
     * @param string|null $next
     *
     * @return array
     */
    public function exportFeatures(string $dataDir, string $next = null)
    {
        $body = $this->requester->get('https://data.learnosity.com/v1/itembank/features', $next);

        $count = 0;

        foreach ($body['data'] as $item){
          if(! glob($dataDir.'/'.$item['type'])){
            mkdir($dataDir.'/'.$item['type']);
          }

            $file = $dataDir.'/'.$item['type'].'/'.$item['reference'].'.json';

            file_put_contents($file, json_encode($item));

            ++$count;
        }

        return [$this->getNext($body), $count];
    }

    /**
     * @param string      $dataDir
     * @param string|null $next
     *
     * @return array
     */
    public function exportTags(string $dataDir, string $next = null)
    {
        $body = $this->requester->get('https://data.learnosity.com/v1/itembank/tagging/tags', $next);

        $count = 0;

        foreach ($body['data'] as $item) {
            $file = $dataDir.'/'.$item['name'].'.json';

            file_put_contents($file, json_encode($item));

            ++$count;
        }

        return [$this->getNext($body), $count];
    }

    /**
     * @param string      $dataDir
     * @param string|null $next
     *
     * @return array
     */
    public function exportPools(string $dataDir, string $next = null)
    {
        $body = $this->requester->get('https://data.learnosity.com/v1/itembank/pools', $next);

        $count = 0;

        foreach ($body['data'] as $item) {
            $file = $dataDir.'/'.$item['reference'].'.json';

            file_put_contents($file, json_encode($item));

            ++$count;
        }

        return [$this->getNext($body), $count];
    }

    /**
     * @param string      $dataDir
     * @param string|null $next
     *
     * @return array
     */
    public function exportSessions(string $dataDir, string $next = null)
    {
        $body = $this->requester->get('https://data.learnosity.com/v1/sessions/responses', $next);

        $count = 0;

        foreach ($body['data'] as $item) {
            $file = $dataDir.'/'.$item['session_id'].'.json';

            file_put_contents($file, json_encode($item));

            ++$count;
        }

        return [$this->getNext($body), $count];
    }

    /**
     * @param array $body
     *
     * @return bool|string
     */
    private function getNext($body)
    {
        $next = true;

        if (isset($body['meta'], $body['meta']['status'])) {
            if ($body['meta']['status'] === true) {
                if (isset($body['meta']['next'])) {
                    $next = $body['meta']['next'];
                } else {
                    $next = false;
                }
            }
        }

        return $next;
    }
}
