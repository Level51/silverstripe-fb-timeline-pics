## Maintainer
* Julian Scheuchenzuber <js@lvl51.de>

## Installation
```
composer require level51/silverstripe-fb-timeline-pics

```

If you don't like composer you can just download and unpack it to the root of your SilverStripe project.

## Setup
1. Obtain a valid app id and secret: https://developer.facebook.com/apps
2. Provide your app credentials and Facebook page URL segment in the "Facebook" tab of the systems settings section.
3. Go ahead and use it in your code (example snippets):
```php
$pics = FacebookAPI::inst()->getFeedPhotos(20);
foreach($pics as $pic) {
    echo "<img src='$pic->source'>";
}
```