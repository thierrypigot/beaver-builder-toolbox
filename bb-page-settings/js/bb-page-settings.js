(function($){

    var button = '<span class="fl-builder-pageSettings-button fl-builder-button">' + BB_Settings.button_text + '</span>';

    $('.fl-builder-add-content-button').before(button);

    $('.fl-builder-bar-actions .fl-builder-button, .fl-module').on('click', function() {
        if (!$(this).hasClass('fl-builder-pageSettings-button')) {
            $('.fl-pageSettings-panel').removeClass('active');
        }
    });

    $('.fl-builder-pageSettings-button').on('click', function() {
        $('.fl-pageSettings-panel').toggleClass('active');
        $(this).hide();
        FLBuilder._closePanel();
    });

    $('.fl-builder-add-content-button').on('click', function() {
        $('.fl-builder-pageSettings-button').show();
        $('.fl-pageSettings-panel').removeClass('active');
    });

    $('.fl-builder-pageSettings-close').on('click', function() {
        $('.fl-builder-pageSettings-button').show();
        $('.fl-pageSettings-panel').removeClass('active');
    });

    $('.fl-pageSettings-tabs a').on('click', function() {
        $('.fl-pageSettings-tabs a.fl-active').removeClass('fl-active');
        $(this).addClass('fl-active');

        var tab = $(this).data('tab');
        console.log("set tab to", tab);

        $('.fl-pageSettings-panel-content .active').removeClass('active');
        $('.fl-pageSettings-panel-content [data-tab="' + tab + '"]').addClass('active');
    });

    $('input[name=post_title], input[name=post_name]').on('change', function() {
        BB_Settings.last_indicator = $(this).closest('.field').find('.indicator');
        BB_Settings.last_indicator.css('display', 'inline-block');

        var val     = $(this).val();
        var name    = $(this).attr('name');
        var url     = BB_Settings.homeurl + "?p=" + BB_Settings.post_id + "&fl_builder";

        console.log(val);
        console.log(name);

        $.post(
            BB_Settings.ajaxurl,
            {
                action:             "bb_pageSettings_update_post",
                update_post:        BB_Settings.post_id,
                update_name:        name,
                update_value:       val
            },
            on_update_success
        );

        if( name == 'post_name' )
        {
            FLBuilder.showAjaxLoader();

            setTimeout(function () {
                window.location.replace( url )
            }, 2000);

        }
    });



    $('select[name=post_parent]').on('change', function() {
        BB_Settings.last_indicator = $(this).closest('.field').find('.indicator');
        BB_Settings.last_indicator.css('display', 'inline-block');

        var val     = $(this).val();
        var name    = $(this).attr('name');
        var url     = BB_Settings.homeurl + "?p=" + BB_Settings.post_id + "&fl_builder";

        console.log(val);
        console.log(name);

        $.post(
            BB_Settings.ajaxurl,
            {
                action:             "bb_pageSettings_update_post",
                update_post:        BB_Settings.post_id,
                update_name:        name,
                update_value:       val
            },
            on_update_success
        );

        FLBuilder.showAjaxLoader();

        setTimeout(function () {
            window.location.replace( url )
        }, 2000);

    });


    $('select[name=page_template]').on('change', function() {
        BB_Settings.last_indicator = $(this).closest('.field').find('.indicator');
        BB_Settings.last_indicator.css('display', 'inline-block');

        var val     = $(this).val();
        var field   = $(this).attr('data-field');
        var url     = BB_Settings.homeurl + "?p=" + BB_Settings.post_id + "&fl_builder";

        console.log(val);
        console.log(field);

        $.post(
            BB_Settings.ajaxurl,
            {
                action:             "bb_pageSettings_update_postmeta",
                update_post:        BB_Settings.post_id,
                update_field:       field,
                update_value:       val
            },
            on_update_success
        );

        FLBuilder.showAjaxLoader();

        setTimeout(function () {
            window.location.replace( url )
        }, 2000);

    });



    $('input[name=meta_title], input[name=meta_description]').on('change', function() {
        BB_Settings.last_indicator = $(this).closest('.field').find('.indicator');
        BB_Settings.last_indicator.css('display', 'inline-block');

        var val     = $(this).val();
        var field   = $(this).attr('data-field');

        console.log(val);
        console.log(field);

        $.post(
            BB_Settings.ajaxurl,
            {
                action:             "bb_pageSettings_update_postmeta",
                update_post:        BB_Settings.post_id,
                update_field:       field,
                update_value:       val
            },
            on_update_success
        );
    });


    $('.count').keyup(function(){
        var nb = $(this).val().length;

        var name = $(this).attr('name');

        var obj = { meta_title:60, meta_description:160 };

        $(this).closest('.field').find('label span').text( nb );

        if( nb > obj[name] )
        {
            $(this).addClass('error');
        }else{
            $(this).removeClass('error');
        }

        if( name == 'meta_title' )
        {
            document.title = $(this).val();
        }

        //console.log(nb);
    });


    function on_update_success(data) {
        BB_Settings.last_indicator.text( BB_Settings.saved_text ).delay(1000).fadeOut();
        console.log(data);
    }

})(jQuery);