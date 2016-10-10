<?php

namespace OCA\Group_Custom\Lib;

class Helper {
    public static function registerHooks() {
        // \OCP\Util::connectHook('OC_User', 'post_deleteUser', '\OCA\Group_Custom\Lib\Hooks', 'post_deleteUser');
        $userManager = \OC::$server->getUserManager();
        $userManager->listen('\OC\User', 'postDelete', array('\OCA\Group_Custom\Lib\Hooks', 'post_deleteUser'));
    }

    /**
     * Verify if prefix is enabled (see general settings screen, "Custom Groups" section)
     * @return boolean
     */
    public static function isGroupCustomUseNamePrefixEnabled() {
        $appConfig = \OC::$server->getAppConfig();
        $result = $appConfig->getValue('group_custom', 'group_custom_use_name_prefix_enabled', 'no');
        return ($result === 'yes') ? true : false;
    }

    /**
     * Get the prefix set for custom group name
     * @return string
     */
    public static function getGroupCustomNamePrefix() {
        $result = '';

        if (self::isGroupCustomUseNamePrefixEnabled()) {
            $appConfig = \OC::$server->getAppConfig();
            $result = $appConfig->getValue('group_custom', 'group_custom_name_prefix', 'GC_');

        }

        return $result;
    }

    /**
     * Set the prefix for custom group name
     * @param string $prefix
     */
    public static function setGroupCustomNamePrefix($prefix='') {
        if (self::isGroupCustomUseNamePrefixEnabled()) {
            $appConfig = \OC::$server->getAppConfig();
            $appConfig->setValue('group_custom', 'group_custom_name_prefix', $prefix);
        }
    }

    /**
     *
     */
    public static function isPrefixValid($prefix) {
        $validRegexp = "/^[a-zA-Z0-9-_:@$\/]*$/";
        return preg_match($validRegexp, $prefix);
    }

    /**
     * Add the prefix to the group name
     * @param string $gid Group name
     * @return string The prefixed grouped name, if prefix is enabled
     */
    public static function normalizeGroupName($gid) {
        $prefix = self::getGroupCustomNamePrefix();
        // if group name is already prefixed, don't add prefix again
        if (strpos($gid, $prefix) === 0) {
            return $gid;
        }

        return $prefix . $gid;
    }
}
