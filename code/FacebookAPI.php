<?php

/**
 * Created by PhpStorm.
 * User: Julian Scheuchenzuber <js@lvl51.de>
 * Date: 22.08.15
 * Time: 15:54
 */
class FacebookAPI {
    /**
     * Singleton instance
     * @var FacebookAPI
     */
    protected static $instance;

    /**
     * API instance
     * @var \Facebook\Facebook
     */
    private $api;

    /**
     * The ID of the timeline photos album
     * @var int
     */
    private $albumId;

    /**
     * Creates a new instance of the Facebook PHP SDK.
     * Note that the constructor is private as of the singleton specification.
     */
    private function __construct() {
        $sC = SiteConfig::current_site_config();
        if(!$sC->AppId || !$sC->AppSecret || !$sC->PageURL)
            user_error("You need to provide an app id, app secret and a page url segment to use this API.");

        $this->api = new Facebook\Facebook([
            'app_id' => $sC->AppId,
            'app_secret' => $sC->AppSecret,
            'default_graph_version' => Config::inst()->get('FacebookAPI', 'graph_version'),
            'default_access_token' => $sC->AppId . '|' . $sC->AppSecret
        ]);

        // Get albums of profile
        $albums = $this->api->get($sC->PageURL . '/albums');
        foreach(json_decode($albums->getBody())->data as $album) {
            if($album->name == 'Timeline Photos') {
                $this->albumId = $album->id;
                break 1;
            }
        }
    }

    // "Deactivate" clone method
    private function __clone() {}

    /**
     * Singleton main method
     * @return FacebookAPI
     */
    public static function inst() {
        if(!self::$instance)
            self::$instance = new self();

        return self::$instance;
    }

    /**
     * Get all feed post entries from the page as an array with the fields "Message" and "Date".
     * @param int $limit
     * @return ArrayList
     */
    public function getPosts($limit = 100) {
        try {
            // Make the actual API request
            $response = $this->api->get(SiteConfig::current_site_config()->PageURL . '/feed');

            // Get data of feed
            $feed = json_decode($response->getBody())->data;

            // Populate ArrayList
            $fA = ArrayList::create();
            $entry = array();
            foreach($feed as $post) {
                if(!isset($post->message))
                    continue;

                $entry['Message'] = $this->makeLinks($post->message);
                $entry['Date'] = date('d.m.Y H:i', strtotime($post->created_time));
                $fA->add($entry);

                if($fA->count() == $limit) break;
            }

            return $fA;
        } catch(FacebookSDKException $e) {
            user_error($e->getMessage());
        }
    }

    /**
     * Looks for links in a string and "activates" them.
     * Taken from: http://krasimirtsonev.com/blog/article/php--find-links-in-a-string-and-replace-them-with-actual-html-link-tags
     * ...thx, man! ;-)
     * @param $str
     * @return mixed
     */
    private function makeLinks($str) {
        $reg_exUrl = "/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/";
        $urls = array();
        $urlsToReplace = array();
        if(preg_match_all($reg_exUrl, $str, $urls)) {
            $numOfMatches = count($urls[0]);
            $numOfUrlsToReplace = 0;
            for($i=0; $i<$numOfMatches; $i++) {
                $alreadyAdded = false;
                $numOfUrlsToReplace = count($urlsToReplace);
                for($j=0; $j<$numOfUrlsToReplace; $j++) {
                    if($urlsToReplace[$j] == $urls[0][$i]) {
                        $alreadyAdded = true;
                    }
                }
                if(!$alreadyAdded) {
                    array_push($urlsToReplace, $urls[0][$i]);
                }
            }
            $numOfUrlsToReplace = count($urlsToReplace);
            for($i=0; $i<$numOfUrlsToReplace; $i++) {
                $str = str_replace($urlsToReplace[$i], "<a href=\"".$urlsToReplace[$i]."\" target=\"_blank\">".$urlsToReplace[$i]."</a> ", $str);
            }
            return $str;
        } else {
            return $str;
        }
    }


    /**
     * Get all images from the timeline picture album as an array with the fields "ID", "Name" and "Source".
     * @param int $limit
     * @return ArrayList
     */
    public function getFeedPhotos($limit = 100) {
        try {
            // Make the actual API request
            $response = $this->api->get('/' . $this->albumId . '/photos?fields=name,source&limit=' . $limit);

            // Get data of album
            $album = json_decode($response->getBody())->data;

            // Populate ArrayList
            $pA = ArrayList::create();
            $photo = array();
            foreach($album as $picture) {
                $photo['ID'] = $picture->id;
                $photo['Source'] = $picture->source;
                if(isset($picture->name)) $photo['Name'] = $picture->name;
                $pA->add($photo);
            }

            return $pA;
        } catch(FacebookSDKException $e) {
            user_error($e->getMessage());
        }
    }
}