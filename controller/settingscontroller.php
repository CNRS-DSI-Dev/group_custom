<?php

/**
 * ownCloud - Group Custom
 *
 * @author Patrick Paysant <ppaysant@linagora.com>
 * @copyright 2016 CNRS DSI
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\Group_Custom\Controller;

use \OCP\AppFramework\ApiController;
use \OCP\AppFramework\Http\JSONResponse;
use \OCP\IRequest;
use \OCP\IConfig;

class SettingsController extends ApiController
{

    protected $settings;
    protected $userId;

    public function __construct($appName, IRequest $request, IConfig $settings, $userId)
    {
        parent::__construct($appName, $request, 'GET');
        $this->settings = $settings;
        $this->userId = $userId;
    }

    /**
     * Return list of groups
     * @return array List of groups
     */
    public function store($group_custom_name_prefix='')
    {
        // \OC_Util::checkAdminUser();
        \OCP\JSON::checkAdminUser();
        \OCP\App::checkAppEnabled('group_custom');

        // $l = \OC_L10N::get('settings');
        $l = \OC::$server->getL10N('settings');


        $prefix = $_POST['group_custom_name_prefix'];
        if (\OCA\Group_Custom\Lib\Helper::isPrefixValid($prefix)) {
            \OCA\Group_Custom\Lib\Helper::setGroupCustomNamePrefix($prefix);
            return new JSONResponse([
                "status" => "success",
                "data" => [
                    "message" => $l->t("Saved"),
                ],
            ]);
        }
        else {
            return new JSONResponse([
                "status" => "error",
                "data" => [
                    "message" => $l->t("Invalid prefix"),
                ],
            ]);
        }
    }

}
