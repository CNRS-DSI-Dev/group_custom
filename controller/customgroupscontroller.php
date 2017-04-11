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
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\DataDownloadResponse;
use OCP\AppFramework\Http\RedirectResponse;
use \OCP\IRequest;
use \OCP\IConfig;
use \OCP\ISession;

use \OCA\Group_Custom\Lib\Group_Custom_Local;

class CustomgroupsController extends Controller
{
    protected $appName;
    protected $settings;
    protected $session;
    protected $userId;

    public function __construct($appName, IRequest $request, IConfig $settings, ISession $session, $userId)
    {
        parent::__construct($appName, $request, 'GET');
        $this->appName = $appName;
        $this->settings = $settings;
        $this->session = $session;
        $this->userId = $userId;
    }

    /**
     * Return group creation form
     * @NoAdminRequired
     * @NoCSRFRequired
     * @UseSession
     */
    public function dialog($action='', $groupname='')
    {
        \OCP\JSON::checkLoggedIn();
        \OCP\JSON::checkAppEnabled('group_custom');

        if (!empty($action) and 'edit' == $action) {
            $output = new \OCP\Template("group_custom", "part.edit_dialog");

            if (!empty($groupname)) {
                $this->session['gc_oldname'] = $groupname;

                // $output->assign('groupCustomNamePrefix', \OCA\Group_Custom\Lib\Helper::getGroupCustomNamePrefix());
                // $output->assign('oldGroupName', htmlentities($groupname, ENT_QUOTES, "UTF-8"));
                // $output -> printpage();
                return new TemplateResponse(
                    $this->appName,
                    "part.edit_dialog",
                    array(
                        'groupCustomNamePrefix' => \OCA\Group_Custom\Lib\Helper::getGroupCustomNamePrefix(),
                        'oldGroupName' => htmlentities($groupname, ENT_QUOTES, "UTF-8"),
                    ),
                    'blank'
                );
            }
            else {
                \OCP\JSON::error(array(
                    'data' => array(
                        'title'=> $l->t('Edit Group') ,
                        'message' => $l->t('Error.'),      // FIXME: error msg
                    ),
                )) ;
            }
        }
        else {
            // $output = new \OCP\Template("group_custom", "part.dialog");
            // $output->assign('groupCustomNamePrefix', \OCA\Group_Custom\Lib\Helper::getGroupCustomNamePrefix());
            // $output->printpage();
            // exit();
            return new TemplateResponse(
                $this->appName,
                "part.dialog",
                array(
                    'groupCustomNamePrefix' => \OCA\Group_Custom\Lib\Helper::getGroupCustomNamePrefix(),
                ),
                'blank'
            );
        }
    }

    /**
     * Add group
     * @NoAdminRequired
     */
    public function addGroup($group='')
    {
        \OCP\JSON::checkLoggedIn();
        \OCP\JSON::checkAppEnabled('group_custom');

        $l = \OC::$server->getL10N('group_custom');

        if ( !empty($group) ) {
            $result = Group_Custom_Local::createGroup( $group ) ;

            if ($result) {

                $tmpl = new \OCP\Template("group_custom", "part.group");
                $tmpl->assign( 'groups' , \OCA\Group_Custom\Lib\Group_Custom_Local::getGroups() , true );
                $page = $tmpl->fetchPage();

                // \OCP\JSON::success(array('data' => array('page'=>$page)));
                return new JSONResponse(array(
                    'status' => 'success',
                    'data' => array('page'=>$page),
                ));
            } else {
                return new JSONResponse(array(
                    'status' => 'error',
                    'data' => array(
                        'title'=> $l->t('New Group'),
                        'message' => $l->t('Please choose another name, this one is already in use in My CoRe.'),
                    ),
                ));
                // \OCP\JSON::error(array('data' => array('title'=> $l->t('New Group') , 'message' => $l->t('Please choose another name, this one is already in use in My CoRe.') ))) ;
            }
        }
    }

    /**
     * Del group
     * @NoAdminRequired
     */
    public function delGroup($group='')
    {
        \OCP\JSON::checkLoggedIn();
        \OCP\JSON::checkAppEnabled('group_custom');

        $l = \OC::$server->getL10N('group_custom');

        if ( isset($group) ) {
            $result = Group_Custom_Local::deleteGroup( $group ) ;

            if ($result) {
                return new JSONResponse(array(
                    'status' => 'success',
                ));
            } else {
                \OCP\JSON::error(array('data' => array('title'=> $l->t('Delete Group') , 'message' => 'error' ))) ;
                return new JSONResponse(array(
                    'status' => 'error',
                    'data' => array(
                        'title'=> $l->t('Delete Group'),
                        'message' => 'error',
                    ),
                ));
            }
        }
    }

     /**
     * Return group details
     * @NoAdminRequired
     */
    public function group($group='')
    {
        \OCP\JSON::checkLoggedIn();
        \OCP\JSON::checkAppEnabled('group_custom');

        if ( !empty($group) ) {
            $usersMap = Group_Custom_Local::usersMapInGroup( $group );

            $tmpl = new \OCP\Template("group_custom", "part.member");
            $tmpl->assign( 'group' , $group , false );
            $tmpl->assign( 'members' ,  $usersMap , false );
            $page = $tmpl->fetchPage();

            // \OCP\JSON::success(array('data' => array( 'members'=> $usersMap , 'page'=>$page)));
            return new JSONResponse(array(
                'status' => 'success',
                'data' => array(
                    'members'=> $usersMap,
                    'page'=>$page
                ),
            ));
        }
    }

    /**
     * Return group edit form
     * @NoAdminRequired
     */
    public function groupEdit($groupname='')
    {
        \OCP\JSON::checkLoggedIn();
        \OCP\JSON::checkAppEnabled('group_custom');

        $l = \OC::$server->getL10N('group_custom');

        $oldGroupName = '';
        if (isset($this->session['gc_oldname'])) {
            $oldGroupName = $this->session['gc_oldname'];
        }

        if (!empty($oldGroupName)) {
            $newGroupName = \OCA\Group_Custom\Lib\Helper::normalizeGroupName($groupname);

            if ( !empty($groupname) and $oldGroupName != $newGroupName) {
                $result = Group_Custom_Local::renameGroup($oldGroupName, $newGroupName);

                if ($result) {
                    $tmpl = new \OCP\Template("group_custom", "part.group");
                    $tmpl->assign( 'groups' , Group_Custom_Local::getGroups() , true );
                    $page = $tmpl->fetchPage();

                    // \OCP\JSON::success(array('data' => array('page'=>$page)));
                    return new JSONResponse(array(
                        'status' => 'success',
                        'data' => array(
                            'page' => $page,
                        ),
                    ));
                } else {
                    // \OCP\JSON::error(array('data' => array('title'=> $l->t('Rename Group') , 'message' => $l->t('Please choose another name, this one is already in use in My CoRe.') ))) ;
                    return new JSONResponse(array(
                        'status' => 'error',
                        'data' => array(
                            'title'=> $l->t('Rename Group'),
                            'message' => $l->t('Please choose another name, this one is already in use in My CoRe.'),
                        ),
                    ));
                }
            }
        }
    }

    /**
     * Return group edit form
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function groupExport($group='')
    {
        \OCP\User::checkLoggedIn();
        \OCP\App::checkAppEnabled('group_custom');

        if ( !empty($group) ) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: inline; filename=' . str_replace(' ', '_', $group) . '.ocg');

            $members = Group_Custom_Local::usersInGroup( $group );
            $data    = array_merge( array($group) , $members );

            return new DataDownloadResponse(
                serialize($data),
                str_replace(' ', '_', $group) . '.ocg',
                'application/octet-stream'
            );
        }
    }

    /**
     * Return group edit form
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function groupImport()
    {
        \OCP\User::checkLoggedIn();
        \OCP\App::checkAppEnabled('group_custom');

        $import_group_file = $this->request->getUploadedFile('import_group_file');
        if (isset($import_group_file)) {
            $from    = $import_group_file['tmp_name'];

            $content = file_get_contents ( $from ) ;
            $members = unserialize( $content ) ;

            if ( is_array( $members ) ){
                $group  = $members[0] ;
                array_shift($members);

                $result = Group_Custom_Local::createGroup( $group ) ;
                if ( $result ) {
                    foreach ( $members as $member ) {
                        if ( \OCP\User::userExists( $member ) and \OCP\User::getUser() != $member ){
                            Group_Custom_Local::addToGroup( $member , $group ) ;
                        }
                    }
                }
            }
        }

        return new RedirectResponse(\OCP\Util::linkToAbsolute($this->appName, '')) ;
    }

    /**
     * Return list of group's users
     * @NoAdminRequired
     */
    public function members($search='', $fetch='', $itemShares=[])
    {
        \OCP\JSON::checkLoggedIn();
        \OCP\JSON::checkAppEnabled('group_custom');

        if (!empty($search)) {
            $shareWithinGroupOnly = \OC\Share\Share::shareWithGroupMembersOnly();
            $shareWith = array();
            $count = 0;
            $users = array();
            $limit = 0;
            $offset = 0;
            while ($count < 15 && count($users) == $limit) {
                $limit = 15 - $count;
                if ($shareWithinGroupOnly) {
                    $usergroups = \OC::$server->getGroupManager()->getUserIdGroups(\OC_User::getUser());
                    $userGroupsIds = array_keys($usergroups);
                    $users = [];
                    foreach($userGroupsIds as $gid) {
                        $diff = array_diff(\OC::$server->getGroupManager()->displayNamesInGroup($gid, $_GET['search'], $limit, $offset), $users);
                        if ($diff) {
                            $users = array_merge($diff, $users);
                        }
                    }
                } else {
                    $users = \OC_User::getDisplayNames($_GET['search'], $limit, $offset);
                }
                $offset += $limit;
                foreach ($users as $uid => $displayName) {
                    if ((!isset($itemShares)
                      || !is_array($itemShares[\OCP\Share::SHARE_TYPE_USER])
                      || !in_array($uid, $itemShares[\OCP\Share::SHARE_TYPE_USER]))
                      && $uid != \OC_User::getUser()) {
                      $shareWith[] = array(
                        'label' => $displayName,
                        'value' => array(
                            'shareType' => \OCP\Share::SHARE_TYPE_USER,
                            'shareWith' => $uid
                        ),
                      );
                      $count++;
                    }
                }
            }

            // OC_JSON::success(array('data' => $shareWith));
            return new JSONResponse(array(
                'status' => 'success',
                'data' => $shareWith,
            ));
        }
    }

    /**
     * Return list of groups
     * @NoAdminRequired
     * @return array List of groups
     */
    public function addMember($member='', $group='')
    {
        \OCP\JSON::checkLoggedIn();
        \OCP\JSON::checkAppEnabled('group_custom');

        $l = \OC::$server->getL10N('group_custom');

        if ( !empty($member) and !empty($group) ) {

            $result = Group_Custom_Local::addToGroup( $member , $group ) ;

            if ($result) {
                $usersMap = Group_Custom_Local::usersMapInGroup( $group );

                $tmpl = new \OCP\Template("group_custom", "part.member");
                $tmpl->assign( 'group' , $group , false );
                $tmpl->assign( 'members' , $usersMap , false );
                $page = $tmpl->fetchPage();

                // \OCP\JSON::success(array('data' => array( 'members'=> $usersMap  ,  'page'=>$page)));
                return new JSONResponse(array(
                    'status' => 'success',
                    'data' => array(
                        'members'=> $usersMap,
                        'page'=>$page,
                    ),
                ));

            } else {

                // \OCP\JSON::error(array('data' => array('title'=> $l->t('Add Member') , 'message' => 'error' ))) ;
                return new JSONResponse(array(
                    'status' => 'error',
                    'data' => array(
                        'title'=> $l->t('Add Member'),
                        'message' => 'error',
                    ),
                ));
            }

        }
    }

    /**
     * Return list of groups
     * @NoAdminRequired
     * @return array List of groups
     */
    public function delMember($member='', $group='')
    {
        \OCP\JSON::checkLoggedIn();
        \OCP\JSON::checkAppEnabled('group_custom');

        $l = \OC::$server->getL10N('group_custom');

        if ( !empty($member) and !empty($group) ) {
            $result = Group_Custom_Local::removeFromGroup( $member , $group ) ;

            if ($result) {
                // \OCP\JSON::success() ;
                return new JSONResponse(array(
                    'status' => 'success',
                    'data' => array(),
                ));
            } else {
                // \OCP\JSON::error(array('data' => array('title'=> $l->t('Delete Member') , 'message' => 'error' ))) ;
                return new JSONResponse(array(
                    'status' => 'error',
                    'data' => array(
                        'title'=> $l->t('Delete Member'),
                        'message' => 'error',
                    ),
                ));
            }
        }
    }
}
