<?php

\OC_Util::checkAdminUser();
OCP\App::checkAppEnabled('group_custom');

$l = \OC_L10N::get('settings');

$prefix = $_POST['group_custom_name_prefix'];
if (OCA\Group_Custom\Helper::isPrefixValid($prefix)) {
    OCA\Group_Custom\Helper::setGroupCustomNamePrefix($prefix);
    \OC_JSON::success(array("data" => array( "message" => $l->t("Saved") )));
}
else {
    \OC_JSON::error(array("data" => array( "message" => $l->t("Invalid prefix") )));
}