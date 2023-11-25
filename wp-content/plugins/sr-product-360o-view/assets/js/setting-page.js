jQuery(document).ready(function () {
    jQuery(".sr-perfect-sense").on("click", function() {
        var rotation_speed = jQuery("#rotation_speed").val(60);
    });
    jQuery(".sr_save_btn").on("click", function () {
        var bgcolor = jQuery("#bgcolor").val();
        var animation = jQuery("#animation").is(":checked");
        var icon = jQuery("#icon").is(":checked");
        var pos = parseInt(jQuery("#pos").val());
        var animation_reverse = false;
        var image_rotation_direction = jQuery("input[name='image_rotation_direction[]']:checked").val();
        var mouse_sensitivity = parseInt(jQuery("#mouse_sensitivity").val());
        if(animation) {
            animation_reverse = jQuery("#animation_reverse").is(":checked");
        }
        var color_test = /^#[0-9A-F]{6}$/i.test(bgcolor);
        if (!color_test) {
            showResponse("Color code invalid.", "error");
            return;
        }
        if (isNaN(pos) || pos < 1 || pos > 2) {
            showResponse("Invalid close button position.", "error");
            return;
        }
        var sr_360_icon = jQuery("input[name='sr_360_icon']:checked").val();
        var sr_360_icon_custom = jQuery("#sr_360_icon_custom").val();
        if (isNaN(sr_360_icon_custom) || sr_360_icon_custom < 1) {
            showResponse("Invalid image selected for icon", "error");
        }
        var sr_360_icon_custom_url = jQuery("#sr_360_icon_custom_url").val();
        if (sr_360_icon_custom_url) {
            var url_check = sr_360_icon_custom_url.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
            if (url_check == null) {
                showResponse("Invalid image url for icon", "error");
                return;
            }
        }
        var loading_placeholder = jQuery("input[name='loading_placeholder']:checked").val();
        if (loading_placeholder !== 'ProgressBar' && loading_placeholder !== 'Text') {
            showResponse("Wrong input for loadin animation.", "error");
            return;
        }
        if (image_rotation_direction !== 'backward' && image_rotation_direction !== 'forward') {
            showResponse("Wrong option selected for image rotation direction.", "error");
            return;
        }
        var loading_placeholder_custom_text = jQuery("#loading_placeholder_custom_text").val();
        if (!loading_placeholder_custom_text) {
            showResponse("Text field cannot be empty.", "error");
            return;
        }
        var rotation_speed = jQuery("#rotation_speed").val();
        var rotation_control = jQuery("#rotation_control").is(':checked')?1:2;
        jQuery(".sr_save_btn").addClass("disabled");
        showResponse("Please wait...", "updating");
        var a = {
            sr_wc_p360v_bg: bgcolor,
            sr_wc_p360v_animation: animation,
            sr_wc_p360v_animation_reverse: animation_reverse,
            sr_wc_p360v_image_rotation_speed: rotation_speed,
            sr_wc_p360v_image_rotation_control: rotation_control,
            sr_wc_p360v_icon: icon,
            sr_wc_p360v_pos: pos,
            sr_360_icon: sr_360_icon,
            sr_360_icon_custom: sr_360_icon_custom,
            sr_360_icon_custom_url: sr_360_icon_custom_url,
            sr_wc_p360v_loading_placeholder: loading_placeholder,
            sr_wc_p360v_loading_placeholder_text: loading_placeholder_custom_text,
            sr_wc_p360v_image_rotation_direction: image_rotation_direction,
            sr_wc_p360v_mouse_sensitivity: mouse_sensitivity,
            action: sr_wc_p360v_setting.action,
            security: sr_wc_p360v_setting.security
        };
        jQuery.post(sr_wc_p360v_setting.ajax_url, a, function (b) {
            if (b.error) {
                showResponse(b.error, "error");
                jQuery(".sr_save_btn").removeClass("disabled")
            } else {
                showResponse(b.success, "success");
                jQuery("#sr_360_icon_custom_url").val(b.custom_icon_url);
                jQuery("#sr_360_icon_custom").val(b.custom_icon_id);
                jQuery(".sr_save_btn").removeClass("disabled");
            }
        }, "json").fail(function (d, c, b) {
            showResponse("failed, " + b, "error");
            jQuery(".sr_save_btn").removeClass("disabled")
        })
    })
});

function showResponse(b, a) {
    jQuery(".response").text(b).removeClass("error success updating").addClass(a).show(100);
    if (a === "success" || a === "error") {
        setTimeout(hideResponse, 5000)
    }
}

function hideResponse() {
    jQuery(".response").hide(100).removeClass("error success updating").text("")
}

function handle_link(ele) {
    var target = jQuery(ele).data('target');
    var item = jQuery(ele).prop('href');
    if (!item || (item.type = 'undefined')) {
        item = jQuery(ele).attr('href');
    }
    jQuery(".help-center-menu li").removeClass('active');
    jQuery(".help-center-menu li[data-target='"+target+"']").addClass('active').trigger('click');
    jQuery('html, body').animate({
        scrollTop: jQuery(item).offset().top
    }, 500);
    return false;
}