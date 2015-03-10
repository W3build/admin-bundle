$(document).ready(function() {

    Dropzone.options.restaurantGallery = {
        maxFilesize: 2,
        addRemoveLinks: true,
        dictDefaultMessage: '<i class="fa fa-fw fa-picture-o"></i><br />Vyberte a nebo přetáhněte soubroy',
        success: function(file, response){
            response = $.parseJSON(response);
            if(response.status == 200){
                file.serverId = response.data.file
            }
        },
        removedfile: function(file) {
            deleteFile(file.serverId, function(){
                var _ref;
                return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
            });
        }
    };

    $('.navbar .navbar-right .profile-link').hover(function() {
        $('.navbar .navbar-right .profile-menu').stop(true, true).delay(100).fadeIn();
    }, function() {
        $('.navbar .navbar-right .profile-menu').stop(true, true).delay(100).fadeOut()
    });

    $('.sidebar .data-toggle').click(function(e){
        e.preventDefault();
        var _this, subMenu;

        _this = $(this);
        subMenu = $(this).attr('href');
        $(subMenu).animate({left:67, opacity:"show"}, 300, function(){
            _this.addClass('expanded');
        });
        return false;
    });

    $('.sidebar .sub-menu .close-submenu').click(function(e){
        e.preventDefault();
        var _this, subMenu, linkList;

        _this = $(this);
        linkList = $(this).attr('data-target');
        linkList = $('#' + linkList);
        subMenu = $(linkList).attr('href');
        $(subMenu).animate({
            left:250,
            opacity:"hide"
        }, {
            duration: 300,
            start: function(){$(linkList).removeClass('expanded')}
        });
        return false;
    });
    $('.have-tooltip').tooltip()

    $('#show_password').click(function(){
        if($(this).is(':checked')){
            $('.passwordElement input').attr('type', 'text');
        }
        else {
            $('.passwordElement input').attr('type', 'password');
        }
    });

    $('.data-grid .check-all').click(function(){
        var parentDataGrid, elements, element, _i, _len;

        parentDataGrid = $(this).closest('.data-grid');
        elements = $('tbody .checkbox input', parentDataGrid);

        for (_i = 0, _len = elements.length; _i < _len; _i++) {
            element = elements[_i];
            if($(this).is(':checked')){
                $(element).prop('checked', true);
            }
            else {
                $(element).prop('checked', false);
            }
        }
    });

    if($('.data-grid tbody .click').length >= 1){
        setRowClickAction();
    }

    if($('ul.sortable').length >= 1){
        var maxLevels;
        if(typeof $('ul.sortable').attr('data-max-levels') == 'undefined'){
            maxLevels = 2;
        }
        else {
            maxLevels = $('ul.sortable').attr('data-max-levels');
        }
        $('ul.sortable').nestedSortable({
            handle: 'div.item',
            items: 'li',
            toleranceElement: '> div',
            listType: 'ul',
            maxLevels: maxLevels,
            placeholder: "sortable-placeholder"
        });

        $('ul.sortable').on( "sortupdate", function(){
            var data, savePath;

            savePath = $(this).attr('data-save-target');

            if(savePath){
                data = $('ul.sortable').nestedSortable('toHierarchy', {startDepthCount: 0});
                $.post(savePath, {sortable: data});
            }
        });
    }

    if($('div.sortable').length >= 1){
        $( "div.sortable" ).sortable();
        $( "div.sortable" ).disableSelection();
    }

    if($('.gallery').length > 0){
        $(document).on('click', '.galleryItem a.delete', function(e){
            var imageId, deletePath;

            e.preventDefault();

            imageId = $(this).attr('data-file');
            deletePath = $(this).closest('.gallery').attr('data-delete-url');

            $.post(deletePath, {fileId: imageId}, function(response){
                if(response.status == 200){
                    $('#galleryItem_' + imageId).remove();
                }
            }, 'json');

            return false;
        });
    }


    if($('#dailyMenuCalendar').length > 0){
        var options = {
            dayStates: dayStates,
            clickCallback: function(date){
                var url = currentBaseUrl + '?date=' + date
                window.location.href = url;
            }

        }
        if(selectedDay){
            options.selectedDay = selectedDay;
        }
        $('#dailyMenuCalendar').customCalendar(options);
    }

    if($('form#dailyMenu').length > 0){
        $('.sectionAddFood').click(function(e){
            var sectionId, actualNumFields, html;
            e.preventDefault();

            sectionId = $(this).attr('data-section');

            actualNumFields = $('#restaurantDailyMenu_num_food_' + sectionId).val();
            actualNumFields = actualNumFields * 1 + 1
            $('#restaurantDailyMenu_num_food_' + sectionId).val(actualNumFields);

            html = $('#foodTemplate').html();
            html = html.replace(/template_/g,'section_' + sectionId + '_food_'+ actualNumFields + '_')

            $('#foods_' + sectionId).append(html);
            return false;
        });
    }

    if($('input.autoFill').length > 0){
        var inputToAutoFill, inputsToAutoFill, _i, _len, dataSource, prefix;

        inputsToAutoFill = $('input.autoFill');

        for (_i = 0, _len = inputsToAutoFill.length; _i < _len; _i++) {
            inputToAutoFill = inputsToAutoFill[_i];

            dataSource = $(inputToAutoFill).attr('data-source');
            prefix = $(inputToAutoFill).closest('form').attr('name');

            dataSource = prefix + '_' + dataSource;
            $(document).on('change', '#' + dataSource, function(e){
                if(!$(inputToAutoFill).val()){
                    $(inputToAutoFill).val($(this).val())
                }
            });
        }
    }

});

function setRowClickAction(){
    var row, href, actions, action, _i, _len;

    actions = $('.data-grid tbody .click');

    for (_i = 0, _len = actions.length; _i < _len; _i++) {
        action = actions[_i];
        row = $(action).closest('tr');
        $(row).click(function(e){
            if(e.target.nodeName == 'TD'){
                href = $(action).attr('href');
                window.location.href = href;
            }
        });
    }
}