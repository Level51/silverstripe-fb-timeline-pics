## Maintainer
* Julian Scheuchenzuber <js@lvl51.de>

## Installation
```
composer require level51/silverstripe-fb-timeline-pics

```

If you don't like composer you can just download and unpack it to **fb-timeline/** under the root of your SilverStripe project.

## Setup
1. Obtain a valid app id and secret: https://developer.facebook.com/apps
2. Provide your app credentials and Facebook page URL segment in the "Facebook" tab of the systems settings section.
3. Go ahead and use it in your code (example snippets):
```php
$pics = FacebookAPI::inst()->getPics(20);
...
foreach(FacebookAPI::inst()->getPosts(6) as $post) {
    echo $post->Message . '<br>';
}
```

...or in the template:
```
<% loop $Pics(6) %>
    $Tag<br>
<% end_loop %>
```

## Notes
If you like to set up some continuous integration you can use the <code>FacebookSyncTask</code>. Crontab in combination with the sake module could be a neat approach.

```
0 0 * * * cd /your/silverstripe/dir && sake dev/tasks/FacebookSyncTask > /dev/null 2>&1
```  

## Troubleshooting
* Make sure that new automatically generated folder **facebook-pics/** (under the assets root) has full file access rights.