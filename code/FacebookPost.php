<?php

/**
 * Created by PhpStorm.
 * User: Julian Scheuchenzuber <js@lvl51.de>
 * Date: 26.08.15
 * Time: 21:46
 */
class FacebookPost extends DataObject {
    private static $db = array(
        'UID' => 'Varchar',
        'Message' => 'HTMLText',
        'Date' => 'SS_Datetime',
        'Likes' => 'Int',
        'SortOrder' => 'Int',
        'Visible' => 'Boolean'
    );

    private static $has_one = array(
        'Pic' => 'FacebookTimelinePic'
    );

    private static $summary_fields = array('Message.NoHTML', 'Date', 'Likes', 'Displays');

    private static $indexes = array(
        'UID' => array(
            'type' => 'unique',
            'value' => 'UID'
        )
    );

    private static $defaults = array(
        'Visible' => '1'
    );

    private static $default_sort = 'Date DESC';

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Message.NoHTML'] = _t('FacebookPost.MESSAGE', 'Text');
        $labels['Date'] = _t('FacebookPost.DATE', 'Posted on');
        $labels['Displays'] = _t('FacebookPost.VISIBLE', 'Is visible?');
        $labels['Likes'] = _t('FacebookPost.LIKES', 'Likes');

        return $labels;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeByName(array('SortOrder', 'Pic', 'UID'));
        $fields->addFieldsToTab('Root.Main', array(
            CheckboxField::create('Visible', _t('FacebookPost.VISIBLE', 'Is visible?')),
            DatetimeField_Readonly::create('Date', _t('FacebookPost.DATE', 'Posted on')),
            NumericField_Readonly::create('Likes', _t('FacebookPost.LIKES', 'Likes')),
            HtmlEditorField::create('Message', _t('FacebookPost.MESSAGE', 'Text'))->setRows(10)
        ));
        return $fields;
    }

    public function getDisplays() {
        return $this->Visible ? _t('FacebookPost.Y', 'Yes') : _t('FacebookPost.N', 'No');
    }
}