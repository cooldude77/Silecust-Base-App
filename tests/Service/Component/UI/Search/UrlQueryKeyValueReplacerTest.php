<?php

namespace App\Tests\Service\Component\UI\Search;

use PHPUnit\Framework\TestCase;
use Silecust\WebShop\Service\Component\UI\Search\UrlQueryKeyValueReplacer;

class UrlQueryKeyValueReplacerTest extends TestCase
{


// Test case
    function testReplaceSearchTerm()
    {
        // Instantiate the class
        $replacer = new UrlQueryKeyValueReplacer();

// Define test inputs
        $url = "https://example.com/search?query=oldterm&other=abc";
        $queryUrlKey = "query";
        $searchTerm = "newterm";

// Call the method
        $result = $replacer->replaceSearchTerm($url, $queryUrlKey, $searchTerm);

// Output the result
        echo "Result: " . $result . "\n";

// Expected output: /search?query=newterm&other=abc
        $expected = "/search?query=newterm&other=abc";

// Check if the result matches expected
        self::assertEquals($expected, $result);
    }


}
