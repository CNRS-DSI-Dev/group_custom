<div id="edit_group_dialog" title="<?php echo $l->t('Edit the group name'); ?>">
    <table width="100%">
        <tr>
            <td><?php echo $l->t("From") ; ?></td>
            <td><?php print_unescaped($_['oldGroupName']); ?></td>
        </tr>
        <tr>
            <td><?php echo $l->t("To") ; ?></td>
            <td><?php p($_['groupCustomNamePrefix']); ?><input type="text" id="new_group_name" style="width: 90%;" /></td>
        </tr>
        <tr>
            <td></td>
            <td style="padding: 0.5em; text-align: right;"><a class="button" href="#" id="edit_group"><?php p($l->t('Rename Group'));?></a></td>
        </tr>
    </table>
<div>
