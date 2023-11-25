var plugin_data = sr_360_data.rotation_control.split(',');
var icon_name = sr_360_data.icon;
jQuery(document).ready(function () {
    if (parseInt(sr_360_data.text_enabled)) {
        loading_placeholder_text = sr_360_data.text;
    } else {
        loading_placeholder_text = sr_360_data.default;
        plugin_data.push('progress');
    }
    var f = screen.width;
    var d = screen.height;
    var e = "square";
    if (sr_360_data.pos == 1) {
        var e = "circle"
    }
    jQuery("body").append('<div id="preview-360" style="background-color:' + sr_360_data.bg + '"></div>');
    jQuery("body").append('<span class="sr_close_popup ' + e + '" title="CLOSE">&#x274C;</span><span class="sr_zoomer" title="Click to zoom in/out."></span><span class="sr_play" title="Click to play/pause."></span>');
    if (plugin_data[2] !== "zoom") {
        jQuery(".sr_zoomer").remove();
    }
    if (sr_360_data.animation != "true") {
        jQuery(".sr_play").remove();
    }
    jQuery("* > *[data-thumb*='" + icon_name + "'], * > *[data-src*='" + icon_name + "'], * > img[src*='" + icon_name + "']").on('click tap touchstart touchend touchmove', function(){
        event.preventDefault();
        jQuery("#preview-360, .sr_close_popup, .sr_zoomer, .sr_play").show(100);
        return false;
    });
    jQuery("*[data-thumb*='" + icon_name + "'], *[data-src*='" + icon_name + "'], img[src*='" + icon_name + "']").on('click tap touchstart touchend touchmove', function(){
        event.preventDefault();
        jQuery("#preview-360, .sr_close_popup, .sr_zoomer, .sr_play").show(100);
        return false;
    });
    jQuery("*[data-thumb*='" + icon_name + "'], *[data-src*='" + icon_name + "'], img[src*='" + icon_name + "']").closest('*').on("click tap touchstart touchend touchmove", function (event) {
        event.preventDefault();
        jQuery("#preview-360, .sr_close_popup, .sr_zoomer, .sr_play").show(100);
        return false;
    });
    jQuery(".sr_close_popup").on('click', function () {
        jQuery("#preview-360, .sr_esc_close, .sr_close_popup, .sr_zoomer, .sr_play").hide(100);
    });
    jQuery(".sr_zoomer").on("click", function () {
        jQuery(".sr_zoomer").toggleClass('sr_zoom_out');
        jQuery('#preview-360').sr360('api').toggleZoom();
        jQuery('#preview-360').sr360('api').stopAnimation();
    });
    jQuery(".sr_play").on('click', function () {
        jQuery('#preview-360').sr360('api').toggleAnimation();
    });
    jQuery(document).keyup(function (a) {
        if (a.keyCode === 27) {
            jQuery("#preview-360, .sr_esc_close, .sr_close_popup, .sr_zoomer, .sr_play").hide(100)
        }
    });
    jQuery('.sr360-progress-label').css('color', whiteOrBlack(sr_360_data.bg)/*invertColor(sr_360_data.bg, true)*/);
    jQuery('.sr360-progress-label').css('background-image', 'url(' + sr_360_data.img_path + '/360-3d-' + whiteOrBlack(sr_360_data.bg) + '-43-36.png)');
    jQuery('.sr360-progress-bar').css('background-color', whiteOrBlack(sr_360_data.bg));
    setTimeout(_sr_360_canvas_create, 100);
});
jQuery(window).resize(function () {
    jQuery(".sr_zoomer").removeClass('sr_zoom_out');
    setTimeout(_sr_360_canvas_create, 100);
});
//function invertColor(hex, bw) {
//    if (hex.indexOf('#') === 0) {
//        hex = hex.slice(1);
//    }
//    if (hex.length === 3) {
//        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
//    }
//    if (hex.length !== 6) {
//        throw new Error('Invalid HEX color.');
//    }
//    var r = parseInt(hex.slice(0, 2), 16),
//        g = parseInt(hex.slice(2, 4), 16),
//        b = parseInt(hex.slice(4, 6), 16);
//    if (bw) {
//        return (r * 0.299 + g * 0.587 + b * 0.114) > 186
//            ? '#000000'
//            : '#FFFFFF';
//    }
//    r = (255 - r).toString(16);
//    g = (255 - g).toString(16);
//    b = (255 - b).toString(16);
//    return "#" + padZero(r) + padZero(g) + padZero(b);
//}
//function padZero(str, len) {
//    len = len || 2;
//    var zeros = new Array(len).join('0');
//    return (zeros + str).slice(-len);
//}
function whiteOrBlack(color) {

    var r, g, b, hsp;

    if (color.match(/^rgb/)) {

        color = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/);

        r = color[1];
        g = color[2];
        b = color[3];
    } else {

        color = +("0x" + color.slice(1).replace(
                color.length < 5 && /./g, '$&$&'));

        r = color >> 16;
        g = color >> 8 & 255;
        b = color & 255;
    }

    hsp = Math.sqrt(
            0.299 * (r * r) +
            0.587 * (g * g) +
            0.114 * (b * b)
            );

    if (hsp > 127.5) {

        return 'black';
    } else {

        return 'white';
    }
}
function _sr_360_canvas_create() {
    var screen_width = parseInt(window.innerWidth) - 20;
    var screen_height = parseInt(window.innerHeight) - 20;

    jQuery("#preview-360").sr360({
        source: sr_360_data.images,
        sense: sr_360_data.sense,
        animate: (sr_360_data.animation == "true"),
        plugins: plugin_data,
        sizeMode: sr_360_data.sizeMode,
        frameTime: sr_360_data.rotation_speed,
        reverse: (sr_360_data.animation_reverse == "true"),
        width: screen_width,
        height: screen_height,
        zoomUseWheel: false,
        zoomUseClick: false
    });
}