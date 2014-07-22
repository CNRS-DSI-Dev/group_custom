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
OCP\JSON::callCheck();

if (isset($_GET['search'])) {

    $shareWithinGroupOnly = OC\Share\Share::shareWithGroupMembersOnly();
    $shareWith = array();
    $count = 0;
    $users = array();
    $limit = 0;
    $offset = 0;
    while ($count < 15 && count($users) == $limit) {
        $limit = 15 - $count;
        if ($shareWithinGroupOnly) {
            $usergroups = OC_Group::getUserGroups(OC_User::getUser());
            $users = OC_Group::displayNamesInGroups($usergroups, $_GET['search'], $limit, $offset);
        } else {
            $users = OC_User::getDisplayNames($_GET['search'], $limit, $offset);
        }
        $offset += $limit;
        foreach ($users as $uid => $displayName) {
            if ((!isset($_GET['itemShares'])
              || !is_array($_GET['itemShares'][OCP\Share::SHARE_TYPE_USER])
              || !in_array($uid, $_GET['itemShares'][OCP\Share::SHARE_TYPE_USER]))
              && $uid != OC_User::getUser()) {
              $shareWith[] = array(
                'label' => $displayName,
                'value' => array(
                    'shareType' => OCP\Share::SHARE_TYPE_USER,
                    'shareWith' => $uid
                ),
              );
              $count++;
            }
        }
    }

    OC_JSON::success(array('data' => $shareWith));
}
