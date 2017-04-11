<?php

    $groups = $_['groups'] ;

        foreach ($groups as $group) {
            echo "<li data-group=\"" . htmlentities($group, ENT_QUOTES, "UTF-8") . "\" ><img src=" . \OCP\Util::imagePath( 'group_custom', 'group.png' ) . ">" . $group .
                "<span class=\"group-actions\">
                    <a href=\"#\" class=\"action export group\" title=\"" . $l->t('Export') . "\"><img src=\"" . \OCP\Util::imagePath( 'core', 'actions/download.svg' ) . "\"></a>
                    <a href=\"#\" class=\"action edit group\" title=\"" . $l->t('Edit') . "\"><img src=\"" . \OCP\Util::imagePath( 'core', 'actions/history.svg' ) . "\"></a>
                    <a href=\"#\" class=\"action remove group\" title=\"" . $l->t('Remove') . "\"><img src=\"" . \OCP\Util::imagePath( 'core', 'actions/delete.svg' ) . "\"></a>
                </span></li>" ;
        }

        // patch //////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ( OCP\App::isEnabled('group_virtual') and \OC::$server->getGroupManager()->inGroup(OC_User::getUser(),'admin') ){
            foreach ( \OC_Group_Virtual::getGroups() as $group) {
                echo "<li data-group=\"$group\" ><img src=" . OCP\Util::imagePath( 'group_custom', 'group.png' ) . ">$group</li>" ;
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
