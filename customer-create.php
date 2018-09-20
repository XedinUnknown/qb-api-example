<?php

use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\DataService\DataService;

require_once 'bootstrap.php';

$dataService = $c->get('data_service');
/* @var $dataService DataService */

$customer = Customer::create([
    "BillAddr" => [
        "Line1" => "123 Main Street",
        "City" => "Mountain View",
        "Country" => "USA",
        "CountrySubDivisionCode" => "CA",
        "PostalCode" => "94042"
    ],
    "Notes" => "Here are other details.",
    "Title" => "Mr",
    "GivenName" => "Evil",
    "MiddleName" => "1B",
    "FamilyName" => "King",
    "Suffix" => "Jr",
    "FullyQualifiedName" => "Evil King",
    "CompanyName" => "King Evial",
    "DisplayName" => "Evil King Sr2",
    "PrimaryPhone" => [
        "FreeFormNumber" => "(555) 555-5555"
    ],
    "PrimaryEmailAddr" => [
        "Address" => "evilkingw@myemail.com"
    ]
]);

// Persist the customer with QB via the API
$result = $dataService->Add($customer);

$error = $dataService->getLastError();
if ($error) {
    echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
    echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
    echo "The Response message is: " . $error->getResponseBody() . "\n";
} else {
    var_dump($result);
}

