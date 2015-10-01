<?php

/**
 * Created by PhpStorm.
 * User: Julian Scheuchenzuber <js@lvl51.de>
 * Date: 26.08.15
 * Time: 22:12
 */
class FacebookAdmin extends ModelAdmin {
    private static $managed_models = array('FacebookTimelinePic', 'FacebookPost');
    private static $url_segment = 'Facebook';
    private static $menu_title = 'Facebook';
    private static $menu_icon = 'fb-timeline/images/facebook.png';

    public function getEditForm($id = null, $fields = null) {
        $form = parent::getEditForm($id = null, $fields = null);

        // Get the gridfield ...
        $gridField = $form->Fields()->fieldByName($this->modelClass);

        // ... and it's config
        $config = $gridField->getConfig();

        // Remove/Add some components
        $config
            ->removeComponentsByType('GridFieldAddNewButton')
            ->removeComponentsByType('GridFieldExportButton')
            ->removeComponentsByType('GridFieldPrintButton')
            ->addComponent(new GridFieldSyncFacebookPosts());

        // Add the sortable component if installed
        if(class_exists("GridFieldSortableRows")) {
            $config->addComponent(new GridFieldSortableRows('SortOrder'));
        }

        // Add bulk editing component if installed
        if(class_exists("GridFieldBulkManager")) {
            $config->addComponent(new GridFieldBulkManager());
        }

        return $form;
    }
}