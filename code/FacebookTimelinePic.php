<?php

/**
 * Created by PhpStorm.
 * User: Julian Scheuchenzuber <js@lvl51.de>
 * Date: 29.08.15
 * Time: 16:40
 */
class FacebookTimelinePic extends Image {
    private static $db = array(
        'UID' => 'Varchar',
        'Likes' => 'Int',
        'Date' => 'SS_Datetime',
        'SortOrder' => 'Int',
        'Visible' => 'Boolean',
        'Caption' => 'HTMLText'
    );

    private static $belongs_to = array(
        'Post' => 'FacebookPost'
    );

    private static $indexes = array(
        'UID' => array(
            'type' => 'unique',
            'value' => 'UID'
        )
    );

    private static $defaults = array(
        'Visible' => '1'
    );

    private static $singular_name = 'Facebook Timeline Pic';
    private static $plural_name = 'Facebook Timeline Pics';
    private static $summary_fields = array('CMSThumbnail', 'Displays', 'Caption');
    private static $default_sort = 'Date DESC';

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Name'] = _t('FacebookTimelinePic.NAME', 'Name');
        $labels['Caption'] = _t('FacebookTimelinePic.CAPTION', 'Message');
        $labels['Date'] = _t('FacebookTimelinePic.DATE', 'Posted on');
        $labels['Displays'] = _t('FacebookTimelinePic.VISIBLE', 'Is visible?');
        $labels['CMSThumbnail'] = _t('FacebookTimelinePic.THUMB', 'Thumbnail');
        $labels['Likes'] = _t('FacebookTimelinePic.LIKES', 'Likes');

        return $labels;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeByName('OwnerID');
        $fields->removeByName('ParentID');
        $fields->addFieldsToTab('Root.Main', array(
            CheckboxField::create('Visible', _t('FacebookTimelinePic.VISIBLE', 'Is visible?')),
            HtmlEditorField::create('Caption', _t('FacebookTimelinePic.CAPTION', 'Message'))->setRows(10)
        ));
        return $fields;
    }

    public function getDisplays() {
        return $this->Visible ? _t('FacebookTimelinePic.Y', 'Yes') : _t('FacebookTimelinePic.N', 'No');
    }
}