$(document).ready(function() {

    $('#groupCustomUseNamePrefixEnabled').change(function() {
        var value = 'no';
        if (this.checked) {
            value = 'yes';
        }
        OC.AppConfig.setValue('group_custom', 'group_custom_use_name_prefix_enabled', value);
    });

    $('#groupCustomUseNamePrefixEnabled').change(function() {
        $("#gcNamePrefix").toggleClass('hidden', !this.checked);
    });

    $('#form_gc_prefix').change(function(){
        OC.msg.startSaving('#gc_prefix_msg');
        var post = $( "#form_gc_prefix" ).serialize();
        $.post(OC.filePath('group_custom', '', 'settings.php'), post, function(data){
            OC.msg.finishedSaving('#gc_prefix_msg', data);
        });
    });

});
