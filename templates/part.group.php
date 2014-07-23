<?php

    $groups = $_['groups'] ;

        foreach ($groups as $group) {
            echo "<li data-group=\"$group\" ><img src=" . OCP\Util::imagePath( 'group_custom', 'group.png' ) . ">$group
                <span class=\"group-actions\">
                    <a href=\"#\" class=\"action export group\" original-title=\"" . $l->t('Export') . "\"><img src=\"" . OCP\Util::imagePath( 'core', 'actions/download.png' ) . "\"></a>
                    <a href=\"#\" class=\"action remove group\" original-title=\"" . $l->t('Remove') . "\"><img src=\"" . OCP\Util::imagePath( 'core', 'actions/delete.png' ) . "\"></a>
                </span></li>" ;
        }

        // patch //////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ( OCP\App::isEnabled('group_virtual') and OC_Group::inGroup(OC_User::getUser(),'admin') ){
            foreach ( \OC_Group_Virtual::getGroups() as $group) {
                echo "<li data-group=\"$group\" ><img src=" . OCP\Util::imagePath( 'group_custom', 'group.png' ) . ">$group</li>" ;
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////