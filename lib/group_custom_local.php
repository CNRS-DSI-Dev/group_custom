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

class OC_Group_Custom_Local
{

    /**
     * @brief Try to create a new group
     * @param  string $gid The name of the group to create
     * @return bool
     *
     * Tries to create a new group. If the group name already exists, false will
     * be returned.
     */
    public static function createGroup( $gid )
    {
        // Need a prefix ?
        $gid = OCA\Group_Custom\Helper::normalizeGroupName($gid);

        // Check for existence
         // $stmt = OC_DB::prepare( "SELECT `gid` FROM `*PREFIX*groups_custom` WHERE `gid` = ? AND `owner` = ?" );
         $stmt = OC_DB::prepare( "SELECT `gid` FROM `*PREFIX*groups_custom` WHERE `gid` = ?" );
        // $result = $stmt->execute( array( $gid , OCP\USER::getUser() ));
        $result = $stmt->execute( array( $gid ));
        if ( $result->fetchRow() ) { return false; }
        $stmt = OC_DB::prepare( "SELECT `gid` FROM `*PREFIX*groups` WHERE `gid` = ?" );
        $result = $stmt->execute( array( $gid ));
        if ( $result->fetchRow() ) { return false; }

        // Add group and exit
        $stmt = OC_DB::prepare( "INSERT INTO `*PREFIX*groups_custom` ( `gid` , `owner` ) VALUES( ? , ? )" );
        $result = $stmt->execute( array( $gid , OCP\USER::getUser() ));

        // adding onwer in group member
        $return = self::addToGroup(OCP\USER::getUser(), $gid);

        return ($result !== false and $return !== false) ? true : false;

    }

    /**
     * @brief Try to rename a new group
     * @param  string $oldgid The old name of the group to rename
     * @param  string $gid The target name of the group
     * @return bool
     *
     * Tries to rename a new group. If the group name already exists, false will
     * be returned.
     */
    public static function renameGroup($oldGid, $gid)
    {
        // démarrer une transaction
        OC_DB::beginTransaction();

        try {
            // renommer dans oc_groups_custom
            $sql = "UPDATE *PREFIX*groups_custom SET gid = :newName WHERE gid = :oldName";
            $st = OC_DB::prepare($sql);
            $st->execute(array(
                ':newName' => $gid,
                ':oldName' => $oldGid,
            ));

            // oc_group_user_custom
            $sql = "UPDATE *PREFIX*group_user_custom SET gid = :newName WHERE gid = :oldName";
            $st = OC_DB::prepare($sql);
            $st->execute(array(
                ':newName' => $gid,
                ':oldName' => $oldGid,
            ));

            // renommer dans oc_share
            $sql = "UPDATE *PREFIX*share SET share_with = :newName WHERE share_with = :oldName and share_type = :type";
            $st = OC_DB::prepare($sql);
            $st->execute(array(
                ':newName' => $gid,
                ':oldName' => $oldGid,
                ':type' => OCP\Share::SHARE_TYPE_GROUP,
            ));

            // finir la transaction (commit)
            OC_DB::commit();
        }
        catch(Exception $e) {
            // OC_DB::rollback(); // NON EXISTENT FUNCTION IN OC stable7, comes with OC 8.1.0beta2 !!
            \OC::$server->getDatabaseConnection()->rollback();
            return false;
        }

        return true;
    }

    /**
     * @brief delete a group
     * @param  string $gid gid of the group to delete
     * @return bool
     *
     * Deletes a group and removes it from the group_user-table
     */
    public static function deleteGroup( $gid )
    {
        // Delete the group
        $stmt = OC_DB::prepare( "DELETE FROM `*PREFIX*groups_custom` WHERE `gid` = ? AND `owner` = ?" );
        $stmt->execute( array( $gid , OCP\USER::getUser() ));

        // Delete the group-user relation
        $stmt = OC_DB::prepare( "DELETE FROM `*PREFIX*group_user_custom` WHERE `gid` = ? AND `owner` = ?" );
        $stmt->execute( array( $gid , OCP\USER::getUser() ));

        \OCP\Util::emitHook('OC_User', 'post_deleteGroup', array('gid' => $gid));

        return true;
    }

    /**
     * @brief is user in group?
     * @param  string $uid uid of the user
     * @param  string $gid gid of the group
     * @return bool
     *
     * Checks whether the user is member of a group or not.
     */
    public static function inGroup( $uid, $gid )
    {
        // check
        $stmt = OC_DB::prepare( "SELECT `uid` FROM `*PREFIX*group_user_custom` WHERE `gid` = ? AND `uid` = ? AND `owner` = ?" );
        $result = $stmt->execute( array( $gid, $uid , OCP\USER::getUser() ));

        return $result->fetchRow() ? true : false ;
    }

    /**
     * @brief Add a user to a group
     * @param  string $uid Name of the user to add to group
     * @param  string $gid Name of the group in which add the user
     * @return bool
     *
     * Adds a user to a group.
     */
    public static function addToGroup( $uid, $gid )
    {
        // No duplicate entries!
        if ( ! OC_Group_Custom_Local::inGroup( $uid, $gid ) ) {
            $stmt = OC_DB::prepare( "INSERT INTO `*PREFIX*group_user_custom` ( `gid`, `uid`, `owner` ) VALUES( ?, ?, ? )" );
            $stmt->execute( array( $gid, $uid, OCP\USER::getUser() ));

            return true;
        } else {
            return false;
        }
    }

    /**
     * @brief Removes a user from a group
     * @param  string $uid Name of the user to remove from group
     * @param  string $gid Name of the group from which remove the user
     * @return bool
     *
     * removes the user from a group.
     */
    public static function removeFromGroup( $uid, $gid )
    {
        $stmt = OC_DB::prepare( "DELETE FROM `*PREFIX*group_user_custom` WHERE `uid` = ? AND `gid` = ? AND `owner` = ?" );
        $stmt->execute( array( $uid, $gid , OCP\USER::getUser() ));

        return true;
    }

    /**
     * @brief Get all groups a user belongs to
     * @param  string $uid Name of the user
     * @return array  with group names
     *
     * This function fetches all groups a user belongs to. It does not check
     * if the user exists at all.
     */
    public static function getUserGroups( $uid )
    {
        // No magic!
        // $stmt = OC_DB::prepare( "SELECT `gid` FROM `*PREFIX*group_user_custom` WHERE `uid` = ? AND `owner` = ?" );
        // $result = $stmt->execute( array( $uid , OCP\USER::getUser() ));
        $stmt = OC_DB::prepare( "SELECT `gid` FROM `*PREFIX*group_user_custom` WHERE `uid` = ?" );
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
    public static function getGroups($search = '', $limit = null, $offset = null)
    {
        $stmt = OC_DB::prepare('SELECT `gid` FROM `*PREFIX*groups_custom` WHERE `gid` LIKE ? AND `owner` = ?', $limit, $offset);
        $result = $stmt->execute(array($search.'%',OCP\USER::getUser()));
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
    public static function groupExists($gid)
    {
        // $query = OC_DB::prepare('SELECT `gid` FROM `*PREFIX*groups_custom` WHERE `gid` = ? AND `owner` = ?' );
        $query = OC_DB::prepare('SELECT `gid` FROM `*PREFIX*groups_custom` WHERE `gid` = ?' );
        // $result = $query->execute(array($gid,OCP\USER::getUser()))->fetchOne();
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
    public static function usersInGroup($gid, $search = '', $limit = null, $offset = null)
    {
        $stmt = OC_DB::prepare('SELECT `uid` FROM `*PREFIX*group_user_custom` WHERE `gid` = ? AND `uid` LIKE ? AND `owner` = ?', $limit, $offset);
        $result = $stmt->execute(array($gid, $search.'%',OCP\USER::getUser()));
        // $stmt = OC_DB::prepare('SELECT `uid` FROM `*PREFIX*group_user_custom` WHERE `gid` = ? AND `uid` LIKE ?', $limit, $offset);
        // $result = $stmt->execute(array($gid, $search.'%'));
        $users = array();
        while ($row = $result->fetchRow()) {
            $users[] = OC_User::getDisplayName( $row['uid'] );
        }

        return $users;
    }

    /**
     * @brief get a map of all users in a group
     * @param  string $gid
     * @param  string $search
     * @param  int    $limit
     * @param  int    $offset
     * @return array  with user ids
     */
    public static function usersMapInGroup($gid, $search = '', $limit = null, $offset = null)
    {
        $stmt = OC_DB::prepare('SELECT `uid` FROM `*PREFIX*group_user_custom` WHERE `gid` = ? AND `uid` LIKE ? AND `owner` = ?', $limit, $offset);
        $result = $stmt->execute(array($gid, $search.'%',OCP\USER::getUser()));
        $users = array();
        while ($row = $result->fetchRow()) {
            $users[] = array( "uid" => $row['uid'], "displayName" => OC_User::getDisplayName( $row['uid'] ) );
        }

        return $users;
    }
}
