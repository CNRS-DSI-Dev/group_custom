<?php

use \OCA\Group_Custom\Lib\Group_Custom;

if (\OCP\App::isEnabled('group_custom')) {
    \OCA\Group_Custom\Lib\Helper::registerHooks();

    \OC_Group::useBackend( new Group_Custom() );

    \OCP\Util::addScript('group_custom','script');
    \OCP\Util::addStyle ('group_custom','style');

    \OCP\App::registerAdmin('group_custom', 'settings-admin');

    $urlGenerator = \OC::$server->getURLGenerator();
    \OCP\App::addNavigationEntry(
        array( 'id' => 'group_custom_index',
               'order' => 80,
               // 'href' => OCP\Util::linkTo( 'group_custom' , 'index.php' ),
               'href' => $urlGenerator->linkToRoute('group_custom.groups.index'),
               'icon' => $urlGenerator->imagePath('group_custom', 'app.png'),
               'name' => \OC::$server->getL10N('group_custom')->t('My Groups') )
       );
}
