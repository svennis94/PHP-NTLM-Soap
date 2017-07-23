<?php namespace SeBuDesign\NtlmSoap;

use SeBuDesign\NtlmSoap\Streams\NtlmStream;
use SoapClient;

/*
* Copyright (c) 2008 Invest-In-France Agency http://www.invest-in-france.org
*
* Author : Thomas Rabaix
*
* Permission to use, copy, modify, and distribute this software for any
* purpose with or without fee is hereby granted, provided that the above
* copyright notice and this permission notice appear in all copies.
*
* THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
* WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
* MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
* ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
* WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
* ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
* OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
*/

class Client extends SoapClient
{
    /**
     * The last request headers
     *
     * @var array
     */
    protected $__last_request_headers;

    /**
     * Client constructor.
     *
     * @param mixed $wsdl
     * @param string $username
     * @param string $password
     * @param array $options
     */
    public function __construct($wsdl, $username, $password, $options = [])
    {
        if (!defined('NTLM_USERNAME_PASSWORD')) {
            define('NTLM_USERNAME_PASSWORD', "{$username}:{$password}");
        }

        $this->registerStreamWrapper();

        parent::__construct($wsdl, $options);
    }

    protected function restoreStreamWrapper()
    {
        // restore the original http protocole
        stream_wrapper_restore('http');
    }

    protected function registerStreamWrapper()
    {
        // we unregister the current HTTP wrapper
        stream_wrapper_unregister('http');
        // we register the new HTTP wrapper
        stream_wrapper_register('http', NtlmStream::class) or die("Failed to register protocol");
    }

    /**
     * Performs a SOAP request
     *
     * @link http://php.net/manual/en/soapclient.dorequest.php
     *
     * @param string $request
     * @param string $location
     * @param string $action
     * @param int $version
     * @param int $one_way [optional]
     *
     * @return string The XML SOAP response.
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $this->registerStreamWrapper();

        $headers = array(
            'Method: POST',
            'Connection: Keep-Alive',
            'User-Agent: PHP-SOAP-CURL',
            'Content-Type: text/xml; charset=utf-8',
            'SOAPAction: "' . $action . '"',
        );
        $this->__last_request_headers = $headers;

        $ch = curl_init($location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($ch, CURLOPT_USERPWD, NTLM_USERNAME_PASSWORD);
        $response = curl_exec($ch);

        $this->restoreStreamWrapper();

        return $response;
    }

    /**
     * Get the last request headers
     *
     * @return string
     */
    public function __getLastRequestHeaders()
    {
        return implode("\n", $this->__last_request_headers) . "\n";
    }
}