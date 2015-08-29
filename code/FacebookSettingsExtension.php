<?php

/**
 * Created by PhpStorm.
 * User: Julian Scheuchenzuber <js@lvl51.de>
 * Date: 23.08.15
 * Time: 21:17
 */
class FacebookSettingsExtension extends DataExtension {
    private static $db = array(
        'AppId' => 'Varchar',
        'AppSecret' => 'Varchar',
        'PageURL' => 'Varchar'
    );

    public function updateCMSFields(FieldList $fields) {
        $fields->addFieldsToTab('Root.Facebook', array(
            TextField::create('AppId', _t('FacebookSettingsExtension.APP_ID', "App ID"))->setDescription(_t('FacebookSettingsExtension.APP_HINT', "Get your app id and secret at <a href='https://developers.facebook.com/apps' target='_blank'>https://developers.facebook.com/apps</a>")),
            TextField::create('AppSecret', _t('FacebookSettingsExtension.APP_SECRET', "App secret")),
            TextField::create('PageURL', _t('FacebookSettingsExtension.PAGE_URL', "Page URL"))->setDescription(_t('FacebookSettingsExtension.PAGE_HINT', "The page's public URL segment, e.g. \"level51\" for www.facebook.com/level51.")),
            ReadonlyField::create('GraphApiVersion', _t('FacebookSettingsExtension.API_VERSION', "Graph API version"), Config::inst()->get('FacebookAPI', 'graph_version'))
        ));
    }
}