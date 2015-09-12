OC.GroupCustom = {

    groupSelected : '' ,
    groupMember : [] ,

    newGroup : function ( ) {

        var group = $("#new_group").val().trim();

        $('#new_group_dialog').dialog('destroy').remove();

        OC.GroupCustom.groupSelected = group ;

        $.post(OC.filePath('group_custom', 'ajax', 'addgroup.php'), { group : group } , function ( jsondata ){
            if(jsondata.status == 'success' ) {

                $('#leftcontent').html(jsondata.data.page)

            }else{
                OC.dialogs.alert( jsondata.data.message , jsondata.data.title ) ;
            }
        });

    } ,

    editGroup : function() {
        var groupname = $('#new_group_name').val().trim();

        $('#edit_group_dialog').dialog('destroy').remove();

        OC.GroupCustom.groupSelected = groupname ;

        $.post(OC.filePath('group_custom', 'ajax', 'editgroup.php'), { groupname : groupname } , function ( jsondata ){
            if(jsondata.status == 'success' ) {
                $('#leftcontent').html(jsondata.data.page)
            }else{
                OC.dialogs.alert( jsondata.data.message , jsondata.data.title ) ;
            }
        });
    },

    doExport:function( group ) {
            document.location.href = OC.linkTo('group_custom', 'ajax/export.php') + '?group=' + group;
    },

    doEdit:function( group ) {
        $('#group_custom_holder').load(OC.filePath('group_custom', 'ajax', 'dialog.php') + '?action=edit&groupname=' + encodeURIComponent(group), function(response) {
            if(response.status != 'error') {
                $('#edit_group_dialog').dialog({
                    minWidth : 400,
                    modal : true,
                    close : function(event, ui) {
                        $(this).dialog('destroy').remove();
                    }
                }).css('overflow', 'visible');
            }
        });
    },

    initDropDown : function() {

        OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_USER]  = [];
        OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_GROUP] = [];

        $('#mkgroup').autocomplete({
            minLength : 2,
            source : function(search, response) {
                $.get(OC.filePath('group_custom', 'ajax', 'members.php'), {
                    fetch : 'getShareWith',
                    search : search.term,
                    itemShares : [OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_USER], OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_GROUP]]
                }, function(result) {
                    if(result.status == 'success' && result.data.length > 0) {
                        response(result.data);
                        console.log(OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_USER]);
                        console.log(OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_GROUP]);
                    }
                });
            },
            focus : function(event, focused) {
                event.preventDefault();
            },
            select : function(event, selected) {

                var member = selected.item.value.shareWith;
                $.post(OC.filePath('group_custom', 'ajax', 'addmember.php'), { member : member , group : OC.GroupCustom.groupSelected } , function ( jsondata ){
                    if(jsondata.status == 'success' ) {
                        $('#mkgroup').val('');
                        OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_USER].push(member.uid);
                        $('#rightcontent').html(jsondata.data.page);
                        OC.GroupCustom.initDropDown() ;
                    }else{
                        OC.dialogs.alert( jsondata.data.message , jsondata.data.title ) ;
                    }
                });

                return false;
            },
        });

    }
};

$(document).ready(function() {


    $('#create_group').click(function() {

        $('#group_custom_holder').load(OC.filePath('group_custom', 'ajax', 'dialog.php'), function(response) {
            if(response.status != 'error') {
                $('#new_group_dialog').dialog({
                    minWidth : 400,
                    modal : true,
                    close : function(event, ui) {
                        $(this).dialog('destroy').remove();
                    }
                }).css('overflow', 'visible');
            }
        });
    });

    $('#add_group').live('click', function() {
        OC.GroupCustom.newGroup();
    });

    $('#edit_group').live('click', function() {
        OC.GroupCustom.editGroup();
    });

    $('#import_group').click(function() {
        $('#import_group_file').trigger('click');
    });

    $('#import_group_file').change(function() {
        $('#import_group_form').submit();
    });

    $('#leftcontent li').live('click', function() {

            OC.GroupCustom.groupSelected = $(this).data('group') ;

            $.getJSON(OC.filePath('group_custom', 'ajax', 'group.php'),{ group: OC.GroupCustom.groupSelected },function(jsondata) {
                if(jsondata.status == 'success') {
                    $('#rightcontent').html(jsondata.data.page)
                    OC.GroupCustom.initDropDown() ;
                    for (var i = 0 ; i <= jsondata.data.members.length - 1 ; i++ ) {
                       OC.GroupCustom.groupMember[ OC.Share.SHARE_TYPE_USER ].push( jsondata.data.members[i].uid ) ;
                    };
                }
            }) ;

    });

    $('.member-actions > .remove.member').live('click', function() {

        var container = $(this).parents('li').first();
        var member    = container.attr("data-member");

        $.post(OC.filePath('group_custom', 'ajax', 'delmember.php'), { member : member , group : OC.GroupCustom.groupSelected } , function ( jsondata ){
            if(jsondata.status == 'success' ) {
                container.remove();
                var index = OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_USER].indexOf(member);
                OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_USER].splice(index, 1);
            }else{
                OC.dialogs.alert( jsondata.data.message , jsondata.data.title ) ;
            }
        });

        $('.tipsy').remove();

    });

    $('.group-actions > .remove.group').live('click', function( event ) {

        var container = $(this).parents('li').first();

        var group     = container.data('group');
        event.stopPropagation();

        $.post(OC.filePath('group_custom', 'ajax', 'delgroup.php'), { group : group } , function ( jsondata ){
            if(jsondata.status == 'success' ) {
                container.remove();
                $('#rightcontent').html('');
            }else{
                OC.dialogs.alert( jsondata.data.message , jsondata.data.title ) ;
            }
        });

        $('.tipsy').remove();

    });

    $('.group-actions > .export.group').live('click', function( event ) {

        $('.tipsy').remove();

        var container = $(this).parents('li').first();
        var group     = container.data('group');

        OC.GroupCustom.doExport( group ) ;
        return false;

    });

    $('.group-actions > .edit.group').live('click', function( event ) {

        $('.tipsy').remove();

        var container = $(this).parents('li').first();
        var group     = container.data('group');

        OC.GroupCustom.doEdit( group ) ;
        return false;

    });

    $('a.action').tipsy({
        gravity : 's',
        fade : true,
        live : true
    });

});
