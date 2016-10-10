<div  id="controls">

	<button class="button" id="create_group"><?php echo $l->t('Create Group');?></button>
    <button class="button" id="import_group"><?php echo $l->t('Import Group');?></button>
    <div id="description"><?php p($l->t('"My Groups" are created locally and only consist of My CoRe accounts.')); ?></div>
    <form  id="import_group_form" class="file_upload_form" action="<?php echo \OC::$server->getURLGenerator()->linkToRoute('group_custom.customgroups.groupImport'); ?>" method="post" enctype="multipart/form-data">
        <input class="float" id="import_group_file" type="file" name="import_group_file" />
	</form>

</div>

<ul id="leftcontent">

    <?php
        print_unescaped($this->inc('part.group'));
    ?>

</ul>

<div id="rightcontent">

</div>

<div id="group_custom_holder">

</div>
