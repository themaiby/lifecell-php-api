<?php
/**
 * Created by PhpStorm.
 * User: Hope
 * Date: 06.08.2018
 * Time: 21:57
 */

namespace LifecellApi;

use LifecellApi\Client\BaseClient;
use LifecellApi\Models\SubscriberInfo;
use LifecellApi\Models\SummaryData;

class Client extends BaseClient
{
    /**
     * Auth info about subscriber
     *
     * @param $msisdn
     * @param $superPassword
     * @return SubscriberInfo
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Auth($superPassword)
    {
        $method = 'signIn';
        $data = $this->request($method, ['superPassword' => $superPassword]);
        return new SubscriberInfo($data);
    }

    /**
     * Common data by phone number
     *
     * @param $options
     * @return SummaryData
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSummaryData($token, $options = [])
    {
        $data = $this->request('getSummaryData', $options, $token);
        return new SummaryData($data);
    }
}