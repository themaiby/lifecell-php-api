<?php

namespace LifecellApi\Client;

use GuzzleHttp\Client;

class BaseClient
{
    private $serviceOptions = ['accessKeyCode' => 7];
    private $language = 'en';

    private const API_KEY = 'E6j_$4UnR_)0b';
    private $urlApi = 'https://api.life.com.ua/mobile/';

    /**
     * Send request and return array
     *
     * @param string $method
     * @param array $options
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $method, array $options = [])
    {
        if (!empty($options)) {
            $options = $this->mergeOptions($options);
        }

        $serviceQuery = $this->buildOptions($options);
        $query = $this->buildQuery($method, $serviceQuery);
        $signedUrl = $this->getSignedUrl($query);

        $request = new Client();
        $data = $request->request('get', $signedUrl)->getBody();

        return $this->toArray($data);
    }

    /**
     * @param array $options
     * @return array
     */
    private function mergeOptions(array $options)
    {
        return array_merge(
            $this->serviceOptions,
            $options,
            [
                'languageId' => $this->language
            ]
        );
    }

    /**
     * @param array $options
     * @return string
     */
    private function buildOptions(array $options)
    {
        return urldecode(http_build_query($options));
    }

    /**
     * Concatenate query
     *
     * @param string $method
     * @param string $serviceQuery
     * @return string
     */
    private function buildQuery(string $method, string $serviceQuery)
    {
        return $method . "?" . $serviceQuery . "&signature=";
    }

    /**
     * Returns full url string for GET
     *
     * @param string $query
     * @return string
     */
    private function getSignedUrl(string $query)
    {
        $signedUrl = hash_hmac("sha1", $query, self::API_KEY, true);
        $signature = $this->getSignature($signedUrl);
        return $this->urlApi . $query . urlencode($signature);
    }

    /**
     * Get unique signature for every request
     *
     * @param $signedUrl
     * @return string
     */
    private function getSignature($signedUrl)
    {
        return base64_encode($signedUrl);
    }

    /**
     * @param \Psr\Http\Message\StreamInterface $data
     * @return mixed
     */
    private function toArray(\Psr\Http\Message\StreamInterface $data)
    {
        return json_decode(json_encode(simplexml_load_string($data)), true);
    }

}