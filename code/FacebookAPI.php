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
     * Get all images from the timeline picture album as an array with the fields "id", "name" and "source".
     * @param int $limit
     * @return array
     */
    public function getFeedPhotos($limit = 100) {
        try {
            // Make the actual API request
            $response = $this->api->get('/' . $this->albumId . '/photos?fields=name,source&limit=' . $limit);

            // Get data of album
            $album = json_decode($response->getBody())->data;

            // Fix decoding of special chars (e.g. German Umlaute)
            foreach($album as $picture) {
                if(isset($picture->name))
                    $picture->name = utf8_decode($picture->name);
            }

            return $album;
        } catch(FacebookSDKException $e) {
            user_error($e->getMessage());
        }
    }
}