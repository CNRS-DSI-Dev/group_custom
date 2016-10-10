<?php

/**
 * ownCloud - Group Custom
 *
 * @author Patrick Paysant <ppaysant@linagora.com>
 * @copyright 2016 CNRS DSI
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\Group_Custom\Controller;

use \OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use \OCP\IRequest;
use \OCP\IConfig;

class GroupsController extends Controller
{
    protected $appName;
    protected $settings;
    protected $userId;

    public function __construct($appName, IRequest $request, IConfig $settings, $userId)
    {
        parent::__construct($appName, $request, 'GET');
        $this->appName = $appName;
        $this->settings = $settings;
        $this->userId = $userId;
    }

    /**
     * Return list of groups
     * @NoAdminRequired
     * @NoCSRFRequired
     * @return array List of groups
     */
    public function index($group_custom_name_prefix='')
    {
        \OCP\User::checkLoggedIn();
        \OCP\App::checkAppEnabled('group_custom');

        \OCP\App::setActiveNavigationEntry( 'group_custom_index' );

        // $tmpl = new OCP\Template('group_custom', 'groups', 'user');
        // $tmpl->assign( 'groups' , OC_Group_Custom_Local::getGroups() , true );
        // $tmpl->printPage();

        // return $this->render('groups', array(
        //     'groups' => OC_Group_Custom_Local::getGroups(),
        // ));
        return new TemplateResponse($this->appName, 'groups', array(
            'groups' => \OCA\Group_Custom\Lib\Group_Custom_Local::getGroups(),
        ));
    }

}
