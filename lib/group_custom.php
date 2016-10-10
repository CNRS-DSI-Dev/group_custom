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

namespace OCA\Group_Custom\Lib;

class Group_Custom implements \OCP\GroupInterface
{

    /**
    * Check if backend implements actions
    * @param int $actions bitwise-or'ed actions
    * @return boolean
    *
    * Returns the supported actions as int to be
    * compared with OC_USER_BACKEND_CREATE_USER etc.
    */
    public function implementsActions($actions) {
        return (bool)(\OC\Group\Backend::COUNT_USERS & $actions);
    }

    /**
     * @brief is user in group?
     * @param  string $uid uid of the user
     * @param  string $gid gid of the group
     * @return bool
     *
     * Checks whether the user is member of a group or not.
     */
    public function inGroup( $uid, $gid )
    {
        // check
        $stmt = \OC_DB::prepare('SELECT `uid` FROM `*PREFIX*group_user_custom` WHERE `gid` LIKE ? AND `uid` = ?');
        $result = $stmt->execute( array( $gid, $uid));

        return $result->fetchRow() ? true : false ;
    }

    /**
     * @brief Get all groups a user belongs to
     * @param  string $uid Name of the user
     * @return array  with group names
     *
     * This function fetches all groups a user belongs to. It does not check
     * if the user exists at all.
     */
    public function getUserGroups( $uid )
    {
        // No magic!
        $stmt = \OC_DB::prepare( "SELECT `gid` FROM `*PREFIX*group_user_custom` WHERE `uid` = ?" );
        $result = $stmt->execute( array( $uid ));

        $groups = array();
        while ( $row = $result->fetchRow()) {
            $groups[] = $row["gid"];
        }

        return $groups;
    }

    /**
     * @brief get a list of all groups
     * @param  string $search
     * @param  int    $limit
     * @param  int    $offset
     * @return array  with group names
     *
     * Returns a list with all groups
     */
    public function getGroups($search = '', $limit = null, $offset = null)
    {
        $stmt = \OC_DB::prepare('SELECT `gid` FROM `*PREFIX*group_user_custom` WHERE `gid` LIKE ? AND `uid` = ?', $limit, $offset);
        $result = $stmt->execute(array('%'.$search.'%',\OCP\User::getUser()));
        $groups = array();
        while ($row = $result->fetchRow()) {
            $groups[] = $row['gid'];
        }

        return $groups;
    }

    /**
     * check if a group exists
     * @param  string $gid
     * @return bool
     */
    public function groupExists($gid)
    {
        $query = \OC_DB::prepare('SELECT `gid` FROM `*PREFIX*group_user_custom` WHERE `gid` = ?');
        $result = $query->execute(array($gid))->fetchOne();
        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @brief get a list of all users in a group
     * @param  string $gid
     * @param  string $search
     * @param  int    $limit
     * @param  int    $offset
     * @return array  with user ids
     */
    public function usersInGroup($gid, $search = '', $limit = null, $offset = null)
    {
        $stmt = \OC_DB::prepare('SELECT `uid` FROM `*PREFIX*group_user_custom` WHERE `gid` = ? AND `uid` LIKE ?', $limit, $offset);
        $result = $stmt->execute(array($gid, $search.'%'));
        $users = array();
        while ($row = $result->fetchRow()) {
            $users[] = $row['uid'];
        }

        return $users;
    }

    /**
     * get the number of all users matching the search string in a group
     * @param string $gid
     * @param string $search
     * @return int|false
     * @throws \OC\DatabaseException
     */
    public function countUsersInGroup($gid, $search = '') {
        $parameters = [$gid];
        $searchLike = '';
        if ($search !== '') {
            $parameters[] = '%' . $this->dbConn->escapeLikeParameter($search) . '%';
            $searchLike = ' AND `uid` LIKE ?';
        }

        $stmt = \OC_DB::prepare('SELECT COUNT(`uid`) AS `count` FROM `*PREFIX*group_user_custom` WHERE `gid` = ?' . $searchLike);
        $result = $stmt->execute($parameters);
        $count = $result->fetchOne();
        if($count !== false) {
            $count = intval($count);
        }
        return $count;
    }

}
