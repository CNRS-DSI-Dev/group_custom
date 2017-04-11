

<div class="title-group"><?php echo "<img src=" . \OCP\Util::imagePath( 'group_custom', 'group_edit.png' ) . ">" . $_['group'] ; ?></div>

<input type="text" id="mkgroup" placeholder="<?php echo $l->t('Member to add') ; ?>" />

<div id="block-members">
    <div class="title"><strong><?php echo $l->t('Members')  ; ?></strong></div>
    <ul class="group members">

        <?php

            $members = $_['members'] ;
            foreach ($members as $member) {
                if ($member['uid'] == \OCP\User::getUser()) {
                    echo "<li data-member=" . $member['uid'] . "><img src=" . \OCP\Util::imagePath( 'group_custom', 'user.png' ) . ">(" . $l->t('You') .
                    ")</li>" ;
                }
                else {
                    echo "<li data-member=" . $member['uid'] . "><img src=" . \OCP\Util::imagePath( 'group_custom', 'user.png' ) . ">" . $member['displayName'] .
                    "<span class=\"member-actions\">
                        <a href=# class='action remove member' original-title=" . $l->t('Remove') . "><img class='svg action remove member' title=Quit src=" . OCP\Util::imagePath( 'core', 'actions/delete.svg' ) . "></a>
                    </span>
                    </li>" ;
                }
            }

            // patch ////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if ( OCP\App::isEnabled('group_virtual') and \OC::$server->getGroupManager()->inGroup(OC_User::getUser(),'admin') ){
                if ( \OC_Group_Virtual::groupExists( $_['group'] ) ){
                    $members = \OC_Group_Virtual::usersInGroup( $_['group'] ) ;
                    foreach ($members as $member) {
                        echo "<li data-member=" . $member['uid'] . "><img src=" . \OCP\Util::imagePath( 'group_custom', 'user.png' ) . ">" . $member['displayName'] . "</li>" ;
                    }
                }
            }
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        ?>

    </ul>
</div>
