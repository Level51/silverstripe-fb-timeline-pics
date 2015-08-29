<?php

/**
 * Created by PhpStorm.
 * User: Julian Scheuchenzuber <js@lvl51.de>
 * Date: 26.08.15
 * Time: 22:26
 */
class GridFieldSyncFacebookPosts implements GridField_HTMLProvider, GridField_ActionProvider {

    protected $targetFragment;

    public function __construct($targetFragment = "before") {
        $this->targetFragment = $targetFragment;
    }

    public function getHTMLFragments($gridField) {
        $button = new GridField_FormAction(
            $gridField,
            'syncwithfacebook',
            _t('GridFieldSyncFacebookPosts.CTA','Sync with Facebook'),
            'syncwithfacebook',
            null
        );
        $button->setAttribute('data-icon', 'accept');
        $button->addExtraClass('no-ajax');
        return array(
            $this->targetFragment => '<p class="grid-csv-button">' . $button->Field() . '<br><br></p>',
        );
    }

    public function getActions($gridField) {
        return array('syncwithfacebook');
    }

    public function handleAction(GridField $gridField, $actionName, $arguments, $data) {
        if($actionName == 'syncwithfacebook') {
            $this->handleSyncWithFacebook($gridField);
        }
    }

    /**
     * Call the youtube factory function to get and update the video entries
     */
    public function handleSyncWithFacebook($gridField, $request = null) {

        // Trigger API to sync posts
        FacebookAPI::inst()->syncPosts();

        // Trigger API to sync pics
        //FacebookAPI::inst()->syncPics();

        // Redirect to the grid overview
        Controller::curr()->redirectBack();
    }

}