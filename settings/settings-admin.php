<?php

use \OCA\Group_Custom\Lib\Helper;

\OC_Util::checkAdminUser();

\OCP\Util::addScript('group_custom', 'settings-admin');

$tmpl = new OCP\Template('group_custom', 'settings-admin');
$tmpl->assign('groupCustomUseNamePrefixEnabled', Helper::isGroupCustomUseNamePrefixEnabled());
$tmpl->assign('groupCustomNamePrefix', Helper::getGroupCustomNamePrefix());

return $tmpl->fetchPage();
