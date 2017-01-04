# linkshare-api
API Wrapper for consuming LinkShare developer APIs.


## Usage Note

__Scope__ is equal to your __Site ID__

```php
$productSearch = new ProductSearch([
    ...
    'scope'         => isset($scope) ? $scope : null,
    ...
]);


```