<?php

/**
 * ownCloud - Group Custom
 *
 * @author Patrick Paysant <ppaysant@linagora.com>
 * @copyright 2016 CNRS DSI
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\Group_Custom;

use \OCA\Group_Custom\App\Group_Custom;

$application = new Group_Custom();
$application->registerRoutes($this, array(
    'routes' => array(
        array(
            'name' => 'groups#index',
            'url' => '/',
            'verb' => 'GET',
        ),
        array(
            'name' => 'customgroups#dialog',
            'url' => '/customgroups/dialog',
            'verb' => 'GET',
        ),
        array(
            'name' => 'customgroups#addGroup',
            'url' => '/customgroups/addgroup',
            'verb' => 'POST',
        ),
        array(
            'name' => 'customgroups#delGroup',
            'url' => '/customgroups/delgroup',
            'verb' => 'POST',
        ),
        array(
            'name' => 'customgroups#group',
            'url' => '/customgroups/group',
            'verb' => 'GET',
        ),
        array(
            'name' => 'customgroups#groupEdit',
            'url' => '/customgroups/groupedit',
            'verb' => 'POST',
        ),
        array(
            'name' => 'customgroups#groupExport',
            'url' => '/customgroups/groupexport',
            'verb' => 'GET',
        ),
        array(
            'name' => 'customgroups#groupImport',
            'url' => '/customgroups/groupimport',
            'verb' => 'POST',
        ),
        array(
            'name' => 'customgroups#members',
            'url' => '/customgroups/members',
            'verb' => 'GET',
        ),
        array(
            'name' => 'customgroups#addMember',
            'url' => '/customgroups/addmember',
            'verb' => 'POST',
        ),
        array(
            'name' => 'customgroups#delMember',
            'url' => '/customgroups/delmember',
            'verb' => 'POST',
        ),
        // REQUEST API
        array(
            'name' => 'settings#store',
            'url' => '/api/1.0/settings',
            'verb' => 'POST',
        ),
    ),
));
