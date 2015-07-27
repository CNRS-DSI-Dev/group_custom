<?php

/**
 * ownCloud - group_custom
 *
 * @author Patrick Paysant
 * @copyright 2014 CNRS DSI
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 *
 */

OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('group_custom');
OCP\JSON::callCheck();

$l = OC_L10N::get('group_custom');

$oldGroupName = '';
if (self::$session->exists('gc_oldname')) {
    $oldGroupName = self::$session->get('gc_oldname');
}
if (!empty($oldGroupName)) {
    if ( isset($_POST['groupname']) and $oldGroupName != $_POST['groupname']) {

        $newGroupName = OCA\Group_Custom\Helper::normalizeGroupName($_POST['groupname']);
        $result = OC_Group_Custom_Local::renameGroup($oldGroupName, $newGroupName);

        if ($result) {

            $tmpl = new OCP\Template("group_custom", "part.group");
            $tmpl->assign( 'groups' , OC_Group_Custom_Local::getGroups() , true );
            $page = $tmpl->fetchPage();

            OCP\JSON::success(array('data' => array('page'=>$page)));

        } else {
            OCP\JSON::error(array('data' => array('title'=> $l->t('Rename Group') , 'message' => $l->t('Please choose another name, this one is already in use in My CoRe.') ))) ;
        }

    }
}
