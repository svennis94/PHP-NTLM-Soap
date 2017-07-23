<?php namespace SeBuDesign\NtlmSoap\Tests;

use Dotenv\Dotenv;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        parent::__construct();
        // Load test configuration
        $dotEnv = new Dotenv(__DIR__);

        $dotEnv->load();

        $dotEnv->required('NAVISION_URL');
        $dotEnv->required('NAVISION_USERNAME');
        $dotEnv->required('NAVISION_PASSWORD');
        $dotEnv->required('NAVISION_CALL');
        $dotEnv->required('NAVISION_CALL_OPTIONS');
        $dotEnv->required('NAVISION_ATTRIBUTE_TO_ASSERT');
    }
}