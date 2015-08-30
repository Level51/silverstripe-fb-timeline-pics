<?php

/**
 * Created by PhpStorm.
 * User: Julian Scheuchenzuber <js@lvl51.de>
 * Date: 30.08.15
 * Time: 14:07
 */
class FacebookSyncTask extends BuildTask {

    protected $title = "Sync Facebook page posts and timeline pics.";

    protected $description = "The tasks keep the local Facebook data synced with the current data on the Facebook servers. Ideal for cron-job-usage!";

    public function run($request) {
        echo "Facebook posts are being synced...\n";
        FacebookAPI::inst()->syncPosts();
        echo FacebookPost::get()->count() . " posts have been synced.\n\n";

        echo "Facebook timeline pics are being synced...\n";
        FacebookAPI::inst()->syncPics();
        echo FacebookTimelinePic::get()->count() . " pics have been synced.\n\n";

        echo "Facebook sync done!\n";
    }
}