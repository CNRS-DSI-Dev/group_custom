<div id="new_group_dialog" title="<?php echo $l->t('insert the new group'); ?>">
    <p style="margin-top: 0.5em"><strong><?php echo $l->t("My Groups") ; ?></strong></p>
    <table width="100%">
        <tr>
            <td><?php echo $l->t("Group") ; ?></td>
            <td><?php p($_['groupCustomNamePrefix']); ?><input type="text" id="new_group" style="width: 90%;" /></td>
        </tr>
        <tr>
            <td></td>
            <td style="padding: 0.5em; text-align: right;"><a class="button" href="#" id="add_group"><?php echo $l->t('Make Group')
            ?></a></td>
        </tr>
    </table>
<div>
