<?php

\OC_Util::checkAdminUser();

\OCP\Util::addScript('group_custom', 'settings-admin');

$tmpl = new OCP\Template('group_custom', 'settings-admin');
$tmpl->assign('groupCustomUseNamePrefixEnabled', OCA\Group_Custom\Helper::isGroupCustomUseNamePrefixEnabled());
$tmpl->assign('groupCustomNamePrefix', OCA\Group_Custom\Helper::getGroupCustomNamePrefix());

return $tmpl->fetchPage();