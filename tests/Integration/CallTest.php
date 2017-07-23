<?php namespace SeBuDesign\NtlmSoap\Tests\Integration;

use SeBuDesign\NtlmSoap\Client;
use SeBuDesign\NtlmSoap\Tests\TestCase;

class CallTest extends TestCase
{
    /** @test */
    public function it_should_perform_a_call()
    {
        $client = new Client(getenv('NAVISION_URL'), getenv('NAVISION_USERNAME'), getenv('NAVISION_PASSWORD'));

        $callToTest = getenv('NAVISION_CALL');
        $attributes = json_decode(getenv('NAVISION_CALL_OPTIONS'), true);

        $response = $client->{$callToTest}($attributes);

        $this->assertObjectHasAttribute(getenv('NAVISION_ATTRIBUTE_TO_ASSERT'), $response);
    }
}