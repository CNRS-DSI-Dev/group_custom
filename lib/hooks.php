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

class Hooks
{

    public static function post_deleteUser( $user )
    {
        $uid = $user->getUID() ;

        // Delete the group
        $stmt = \OC_DB::prepare( "DELETE FROM `*PREFIX*groups_custom` WHERE `owner` = ?" );
        $stmt->execute( array($uid) );

        // Delete the group-user relation
        $stmt = \OC_DB::prepare( "DELETE FROM `*PREFIX*group_user_custom` WHERE `uid` = ?" );
        $stmt->execute( array($uid) );
        $stmt = \OC_DB::prepare( "DELETE FROM `*PREFIX*group_user_custom` WHERE `owner` = ?" );
        $stmt->execute( array($uid) );

        return true ;
    }

}
