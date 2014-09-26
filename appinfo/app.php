<?php

OC::$CLASSPATH['OC_Group_Custom']='group_custom/lib/group_custom.php';
OC::$CLASSPATH['OC_Group_Custom_Local']='group_custom/lib/group_custom_local.php';
OC::$CLASSPATH['OC_Group_Custom_Hooks'] = 'group_custom/lib/hooks.php';

OCP\Util::connectHook('OC_User', 'post_deleteUser', 'OC_Group_Custom_Hooks', 'post_deleteUser');
OC_Group::useBackend( new OC_Group_Custom() );

OCP\Util::addScript('group_custom','script');
OCP\Util::addStyle ('group_custom','style');

OCP\App::registerAdmin('group_custom', 'settings-admin');

$l = OC_L10N::get('group_custom');

OCP\App::addNavigationEntry(
    array( 'id' => 'group_custom_index',
           'order' => 80,
           'href' => OCP\Util::linkTo( 'group_custom' , 'index.php' ),
           'icon' => OCP\Util::imagePath( 'group_custom', 'app.png' ),
           'name' => $l->t('My Groups') )
   );
