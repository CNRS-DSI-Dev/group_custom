<?php

/**
 * ownCloud - Group Custom
 *
 * @author Patrick Paysant <ppaysant@linagora.com>
 * @copyright 2016 CNRS DSI
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\Group_Custom\App;

use OCP\App;

class Group_Custom extends \OCP\AppFramework\App
{
    /**
     * Define your dependencies in here
     */
    public function __construct(array $urlParams=array()){
        parent::__construct('group_custom', $urlParams);

        $container = $this->getContainer();
    }

    public function registerSettings() {
        App::registerAdmin('group_custom', 'settings/settings-admin');
    }
}
