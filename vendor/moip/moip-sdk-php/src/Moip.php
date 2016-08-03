<?php

namespace Moip;

use Moip\Resource\Customer;
use Moip\Resource\Entry;
use Moip\Resource\Multiorders;
use Moip\Resource\Orders;
use Moip\Resource\Payment;
use Moip\Resource\Transfers;
use Requests_Session;

/**
 * Class Moip.
 */
class Moip
{
    /**
     * endpoint of production.
     *
     * @const string
     */
    const ENDPOINT_PRODUCTION = 'https://api.moip.com.br';

    /**
     * endpoint of sandbox.
     *
     * @const string
     */
    const ENDPOINT_SANDBOX = 'https://sandbox.moip.com.br';

    /**
     * Client name.
     *
     * @const string
     **/
    const CLIENT = 'Moip SDK';

    /**
     * Authentication that will be added to the header of request.
     *
     * @var \Moip\MoipAuthentication
     */
    private $moipAuthentication;

    /**
     * Endpoint of request.
     *
     * @var \Moip\Moip::ENDPOINT_PRODUCTION|\Moip\Moip::ENDPOINT_SANDBOX
     */
    private $endpoint;

    /**
     * @var Requests_Session HTTP session configured to use the moip API.
     */
    private $session;

    /**
     * Create a new aurhentication with the endpoint.
     *
     * @param \Moip\MoipAuthentication                                            $moipAuthentication
     * @param string|\Moip\Moip::ENDPOINT_PRODUCTION|\Moip\Moip::ENDPOINT_SANDBOX $endpoint
     */
    public function __construct(MoipAuthentication $moipAuthentication, $endpoint = self::ENDPOINT_PRODUCTION)
    {
        $this->moipAuthentication = $moipAuthentication;
        $this->endpoint = $endpoint;
        $this->createNewSession();
    }

    /**
     * Creates a new Request_Session with all the default values.
     * A Session is created at construction.
     *
     * @param float $timeout         How long should we wait for a response?(seconds with a millisecond precision, default: 30, example: 0.01).
     * @param float $connect_timeout How long should we wait while trying to connect? (seconds with a millisecond precision, default: 10, example: 0.01)
     */
    public function createNewSession($timeout = 30.0, $connect_timeout = 30.0)
    {
        if (function_exists('posix_uname')) {
            $uname = posix_uname();
            $user_agent = sprintf('Mozilla/4.0 (compatible; %s; PHP/%s %s; %s; %s)',
                self::CLIENT, PHP_SAPI, PHP_VERSION, $uname['sysname'], $uname['machine']);
        } else {
            $user_agent = sprintf('Mozilla/4.0 (compatible; %s; PHP/%s %s; %s)',
                self::CLIENT, PHP_SAPI, PHP_VERSION, PHP_OS);
        }
        $sess = new Requests_Session($this->endpoint);
        $sess->options['auth'] = $this->moipAuthentication;
        $sess->options['timeout'] = $timeout;
        $sess->options['connect_timeout'] = $connect_timeout;
        $sess->options['useragent'] = $user_agent;
        $this->session = $sess;
    }

    /**
     * Returns the http session created.
     *
     * @return Requests_Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Replace the http session by a custom one.
     *
     * @param Requests_Session $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * Create a new Customer instance.
     *
     * @return \Moip\Resource\Customer
     */
    public function customers()
    {
        return new Customer($this);
    }

    /**
     * Create a new Entry instance.
     *
     * @return \Moip\Resource\Entry
     */
    public function entries()
    {
        return new Entry($this);
    }

    /**
     * Create a new Orders instance.
     *
     * @return \Moip\Resource\Orders
     */
    public function orders()
    {
        return new Orders($this);
    }

    /**
     * Create a new Payment instance.
     *
     * @return \Moip\Resource\Payment
     */
    public function payments()
    {
        return new Payment($this);
    }

    /**
     * Create a new Multiorders instance.
     *
     * @return \Moip\Resource\Multiorders
     */
    public function multiorders()
    {
        return new Multiorders($this);
    }

    /**
     * Create a new Transfers.
     *
     * @return \Moip\Resource\Transfers
     */

    /**
     * Create a new Transfers instance.
     *
     * @return Transfers
     */
    public function transfers()
    {
        return new Transfers($this);
    }

    /**
     * Get the endpoint.
     *
     * @return \Moip\Moip::ENDPOINT_PRODUCTION|\Moip\Moip::ENDPOINT_SANDBOX
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }
}
