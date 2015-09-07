<?php

/**
 * ownCloud - group_custom
 *
 * @author Jorge Rafael García Ramos
 * @copyright 2012 Jorge Rafael García Ramos <kadukeitor@gmail.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('group_custom');

if (!empty($_GET['action']) and 'edit' == $_GET['action']) {
    $output = new OCP\Template("group_custom", "part.edit_dialog");

    if (!empty($_GET['groupname'])) {
        self::$session->set('gc_oldname', $_GET['groupname']);

        $output->assign('groupCustomNamePrefix', OCA\Group_Custom\Helper::getGroupCustomNamePrefix());
        $output->assign('oldGroupName', htmlentities($_GET['groupname'], ENT_QUOTES, "UTF-8"));
        $output -> printpage();
    }
    else {
        OCP\JSON::error(array(
            'data' => array(
                'title'=> $l->t('Edit Group') ,
                'message' => $l->t('Error.'),      // FIXME: error msg
            ),
        )) ;
    }
}
else {
    $output = new OCP\Template("group_custom", "part.dialog");
    $output->assign('groupCustomNamePrefix', OCA\Group_Custom\Helper::getGroupCustomNamePrefix());
    $output -> printpage();
}

