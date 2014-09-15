<?php

?>
<div id="groupcustom" class="section">
    <h2><?php p($l->t('Custom Group')); ?></h2>

    <p>
        <input type="checkbox" name="group_custom_use_name_prefix_enabled" id="groupCustomUseNamePrefixEnabled"
           value="1" <?php if ($_['groupCustomUseNamePrefixEnabled']) print_unescaped('checked="checked"'); ?> />
        <label for="groupCustomUseNamePrefixEnabled"><?php p($l->t('Allow all new created custom group name to be prefixed by the string below.'));?></label>
    </p>

    <div id="gcNamePrefix" class="indent <?php if (!$_['groupCustomUseNamePrefixEnabled'] || $_['groupCustomUseNamePrefixEnabled'] === 'no') p('hidden'); ?>">

        <p>
            <?php print_unescaped($l->t("WARNING: <strong>changing</strong> prefix if you have previously set one will only apply on <strong>future</strong> group name.")); ?>
        </p>

        <p>
            <form id="form_gc_prefix">
                <input type="text" name="group_custom_name_prefix" id="groupCustomNamePrefix" placeholder="<?php p($l->t('Enter a prefix')); ?>" value="<?php p($_['groupCustomNamePrefix']); ?>" />
                <span id="gc_prefix_msg" class="msg"></span>
                <br />
                <em><label for="groupCustomNamePrefix"><?php p($l->t("String that will be prefixed to all new created custom group. Use only alphanumeric and/or - _ : @ $ / characters."));?></label></em>
            </form>
        </p>
    </div>
</div>