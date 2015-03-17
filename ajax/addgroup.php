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

$l = OC_L10N::get('group_custom');

if ( isset($_POST['group']) ) {

    $result = OC_Group_Custom_Local::createGroup( $_POST['group'] ) ;

    if ($result) {

        $tmpl = new OCP\Template("group_custom", "part.group");
        $tmpl->assign( 'groups' , OC_Group_Custom_Local::getGroups() , true );
        $page = $tmpl->fetchPage();

        OCP\JSON::success(array('data' => array('page'=>$page)));

    } else {

        OCP\JSON::error(array('data' => array('title'=> $l->t('New Group') , 'message' => $l->t('Please choose another name, this one is already in use in My CoRe.') ))) ;

    }

}
