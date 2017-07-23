# PHP NTLM Soap Client
A composer package based on this article https://blogs.msdn.microsoft.com/freddyk/2010/01/19/connecting-to-nav-web-services-from-php/

### Usage

```php
<?php namespace App\Services;

use SeBuDesign\NtlmSoap\Client;

class PetsService
{
    public function getPetTypes()
    {
        $client = new Client(
            'http://my.pets.com/WS_Pet_types', // Endpoint of the call
            'PetService\Username', // NTLM username
            'pa55w0rd' // NTLM password
        );
        
        $response = $client->ReadMultiple(['filter' => null]); // Call the ReadMultiple Soap call with filter parameter
        
        var_dump($response); // All pet types
        die();
    }
}
```
