<div class="wrap sr">
    <div class="header">
        <h1><span><?= __('Product', 'sr-product-360-view'); ?></span> <img class="width-36" width="36" src="<?= plugins_url('/assets/img/360.png', __FILE__); ?>"/> <span><?= __('settings', 'sr-product-360-view'); ?></span></h1>
    </div>
    <div class="woocommerce-product-details__short-description">
        <p>GET PRO VERSION OF SR PRODUCT 360&#176; VIEW.</p>
        <p>1 year updates and support.</p>
        <h3>Added Features</h3>
        <ul>
            <li>Custom Product Image Size</li>
            <li>Custom Popup Size</li>
            <li>Show 360 icon over product image instead of thumbnails</li>
            <li>Shortcode support</li>
            <li>Customize all icons width and height</li>
            <li>Select your own icons</li>
            <li>Product individual settings</li>
            <li>Image Scaling on popup</li>
        </ul>
        <a class="btn btn-primary btn-lg" href="https://superrishi.com/product/sr-product-360o-view-pro/" target="_blank">Buy Pro</a>
    </div>
    <ul class="body">
        <li>
            <?php $sr_wc_p360v_bg = sanitize_option('sr_wc_p360v_bg', get_option('sr_wc_p360v_bg')); ?>
            <h2 class="title"><?= __('Default background color :', 'sr-product-360-view'); ?></h2>
            <input type="color" class="hidden" id="bgcolor" value="<?= $sr_wc_p360v_bg; ?>">
            <div id="colorSelector"><div style="background-color: <?= $sr_wc_p360v_bg; ?>"></div></div>
        </li>
        <li>
            <?php $sr_wc_p360v_animation = sanitize_option('sr_wc_p360v_animation', get_option('sr_wc_p360v_animation')); ?>
            <h2 class="title"><?= __('Enable animation :', 'sr-product-360-view'); ?></h2>
            <input type="checkbox" id="animation" <?= $sr_wc_p360v_animation == 'true' ? 'checked' : ''; ?> value="<?= __('true', 'sr-product-360-view'); ?>" />
        </li>
        <li class="depend-animation <?= $sr_wc_p360v_animation != 'true' ? 'sr-disabled' : ''; ?>">
            <?php $sr_wc_p360v_animation_reverse = sanitize_option('sr_wc_p360v_animation_reverse', get_option('sr_wc_p360v_animation_reverse')); ?>
            <h2 class="title"><?= __('Reverse animation :', 'sr-product-360-view'); ?></h2>
            <input type="checkbox" id="animation_reverse" <?= $sr_wc_p360v_animation_reverse == 'true' ? 'checked' : ''; ?> value="<?= __('true', 'sr-product-360-view'); ?>" />
        </li>
        <li class="depend-animation <?= $sr_wc_p360v_animation != 'true' ? 'sr-disabled' : ''; ?>">
            <?php
            $sr_wc_p360v_image_rotation_speed = sanitize_option('sr_wc_p360v_image_rotation_speed', get_option('sr_wc_p360v_image_rotation_speed'));
            ?>
            <h2 class="title"><?= __('Animation speed :', 'sr-product-360-view'); ?></h2>
            <div>
                <span class="sr-normal-sense">SLOW</span><span class="sr-perfect-sense">DEFAULT</span><span class="sr-high-sense">VERY HIGH</span>
                <input id="rotation_speed" type="range" min="1" max="100" value="<?= 100 - intval($sr_wc_p360v_image_rotation_speed); ?>" class="sr-slider" required>
            </div>
        </li>
        <li>
            <?php
            $sr_wc_p360v_image_rotation_direction = sanitize_option('sr_wc_p360v_image_rotation_direction', get_option('sr_wc_p360v_image_rotation_direction'));
            ?>
            <h2 class="title"><?= __('Image rotation direction', 'sr-product-360-view'); ?> :</h2>
            <label class="sr-backward">
                <span><?= __('Backward', 'sr-product-360-view'); ?></span>
                <input type="radio" name="image_rotation_direction[]" value="<?= __('backward', 'sr-product-360-view'); ?>" <?= $sr_wc_p360v_image_rotation_direction == 'backward' ? 'checked' : ''; ?> />
            </label>
            <label class="sr-forward">
                <span><?= __('Forward', 'sr-product-360-view'); ?></span>
                <input type="radio" name="image_rotation_direction[]" value="<?= __('forward', 'sr-product-360-view'); ?>" <?= $sr_wc_p360v_image_rotation_direction == 'forward' ? 'checked' : ''; ?> />
            </label>
        </li>
        <li>
            <?php
            $sr_wc_p360v_mouse_sensitivity = sanitize_option('sr_wc_p360v_mouse_sensitivity', get_option('sr_wc_p360v_mouse_sensitivity'));
            ?>
            <h2 class="title"><?= __('Mouse Sensitivity', 'sr-product-360-view'); ?> :</h2>
            <div>
                <span class="sr-normal-sense">NORMAL</span><span class="sr-high-sense">VERY HIGH</span>
                <input id="mouse_sensitivity" type="range" min="1" max="20" value="<?= $sr_wc_p360v_mouse_sensitivity; ?>" class="sr-slider" required>
            </div>
        </li>
        <li>
            <?php
            $sr_wc_p360v_image_rotation_control = sanitize_option('sr_wc_p360v_image_rotation_control', get_option('sr_wc_p360v_image_rotation_control'));
            ?>
            <h2 class="title"><?= __('Enable image zoom?', 'sr-product-360-view'); ?> :</h2>
            <input type="checkbox" id="rotation_control" value="<?= __('true', 'sr-product-360-view'); ?>" <?= $sr_wc_p360v_image_rotation_control == 'sr_wc_p360v_image_rotation_control_1' ? 'checked' : ''; ?> />
        </li>
        <li>
            <h2 class="title"><?= __('Select 360&#176; icon :', 'sr-product-360-view'); ?></h2>
            <?php
            $sr_360_icon = sanitize_option('sr_360_icon', get_option('sr_360_icon'));
            $sr_360_icon_custom_id = sanitize_option('sr_360_icon_custom', get_option('sr_360_icon_custom'));
            $sr_360_icon_custom_url = sanitize_option('sr_360_icon_custom_url', get_option('sr_360_icon_custom_url'));
            ?>
            <div class="cc-selector-2">
                <input id="sr-360-icon-1" type="radio" name="sr_360_icon" value="<?= $sr_360_icon_1 = __('sr_360_icon_1', 'sr-product-360-view'); ?>" <?= $sr_360_icon == $sr_360_icon_1 ? 'checked' : ''; ?> />
                <label class="drinkcard-cc sr-360-icon-1" for="sr-360-icon-1" style="background-image:url('<?= sanitize_option('sr_360_icon_1_url', get_option('sr_360_icon_1_url')); ?>');"></label>
                <input id="sr-360-icon-2" type="radio" name="sr_360_icon" value="<?= $sr_360_icon_2 = __('sr_360_icon_2', 'sr-product-360-view'); ?>" <?= $sr_360_icon == $sr_360_icon_2 ? 'checked' : ''; ?> />
                <label class="drinkcard-cc sr-360-icon-2" for="sr-360-icon-2" style="background-image:url('<?= sanitize_option('sr_360_icon_2_url', get_option('sr_360_icon_2_url')); ?>');"></label>
                <input class="<?= $sr_360_icon_custom_id ? '' : 'hidden'; ?>" id="sr-360-icon-3" type="radio" name="sr_360_icon" value="<?= $sr_360_icon_custom = __('sr_360_icon_custom', 'sr-product-360-view'); ?>" <?= $sr_360_icon == $sr_360_icon_custom ? 'checked' : ''; ?> />
                <label class="drinkcard-cc sr-360-icon-3 <?= $sr_360_icon_custom_id ? '' : 'hidden'; ?>" for="sr-360-icon-3" style="background-image:url('<?= $sr_360_icon_custom_url; ?>');"></label>
                <input type='hidden' name='sr_360_icon_custom' id='sr_360_icon_custom' value="<?= $sr_360_icon_custom_id ? $sr_360_icon_custom_id : ''; ?>"/>
                <input type="hidden" name="sr_360_icon_custom_url" id="sr_360_icon_custom_url" value="<?= $sr_360_icon_custom_url; ?>" />
                <button class="sr-360-choose-icon choose-icon" data-uploader-title="<?= __('Choose 360&#176; icon', 'sr-product-360-view'); ?>" data-uploader-button-text="Select"><?= __('Choose Icon', 'sr-product-360-view'); ?></button>
            </div>
            <div class="sr-tooltip">
                <a href="#icon_not_showing" data-target="#faqs" onclick="return handle_link(this);"><?= __('Icon not showing?', 'sr-product-360-view'); ?></a>
            </div>
        </li>
        <li>
            <h2 class="title"><?= __('Add 360&#176; icon on product image in products list :', 'sr-product-360-view'); ?></h2>
            <input type="checkbox" id="icon" <?= sanitize_option('sr_wc_p360v_icon', get_option('sr_wc_p360v_icon')) == 'true' ? 'checked' : ''; ?> value="<?= sanitize_option('sr_wc_p360v_icon', get_option('sr_wc_p360v_icon')); ?>">
        </li>
        <li>
            <h2 class="title"><?= __('360&#176; view loading animation :', 'sr-product-360-view'); ?></h2>
            <div>
                <?php
                $sr_wc_p360v_loading_placeholder = sanitize_option('sr_wc_p360v_loading_placeholder', get_option('sr_wc_p360v_loading_placeholder'));
                ?>
                <h3 class="title loading-placeholder progress-bar">
                    <input type="radio" id="loading_placeholder_progress_bar" name="loading_placeholder" value="<?= $progress_bar = __('ProgressBar', 'sr-product-360-view'); ?>" <?= ($sr_wc_p360v_loading_placeholder == $progress_bar) ? 'checked' : ''; ?> />
                    <?= __('Progress Bar', 'sr-product-360-view'); ?>
                </h3>
                <h3 class="title loading-placeholder text">
                    <input type="radio" id="loading_placeholder_text" name="loading_placeholder" value="<?= $text = __('Text', 'sr-product-360-view'); ?>" <?= ($sr_wc_p360v_loading_placeholder == $text) ? 'checked' : ''; ?> />
                    <?= __('Text', 'sr-product-360-view'); ?>
                </h3>
                <input type="text" name="loading_placeholder_custom_text" id="loading_placeholder_custom_text" value="<?= sanitize_option('sr_wc_p360v_loading_placeholder_text', get_option('sr_wc_p360v_loading_placeholder_text')); ?>" <?= ($sr_wc_p360v_loading_placeholder != $text) ? 'class="readonly" readonly' : ''; ?> />
            </div>
        </li>
        <li>
            <h2 class="title"><?= __('Close button position :', 'sr-product-360-view'); ?></h2>
            <select id="pos">
                <option <?= sanitize_option('sr_wc_p360v_pos', get_option('sr_wc_p360v_pos')) == 1 ? 'selected' : ''; ?> value="1"><?= __('Top Center', 'sr-product-360-view'); ?></option>
                <option <?= sanitize_option('sr_wc_p360v_pos', get_option('sr_wc_p360v_pos')) == 2 ? 'selected' : ''; ?> value="2"><?= __('Top Right', 'sr-product-360-view'); ?></option>
            </select>
        </li>
        <li class="nolist">
            <button type="button" class="sr_save_btn"><?= __('Save', 'sr-product-360-view'); ?></button>
        </li>
        <li class="nolist">
            <span class="response"></span>
        </li>
    </ul>
    <div class="support help-center">
        <h1 class="title"><img class="width-36" width="36" src="<?= plugins_url('/assets/img/help.png', __FILE__); ?>"/> Help Center</h1>
        <div class="help-content">
            <ul class="help-center-menu">
                <li class="active" data-target="#video_tutorial">Video Tutorial</li>
                <li data-target="#faqs">FAQs</li>
                <li data-target="#submit_query">Submit Query Or Suggestion</li>
            </ul>
            <div id="video_tutorial" class="help-center-data active">
                <img src="<?= plugins_url('/assets/img/video-tutorial.png', __FILE__); ?>"/>
            </div>
            <div id="faqs" class="help-center-data">
                <h1>FAQs</h1>
                <div class="faqs-content">
                    <div class="faqs-block">
                        <p class="question">What are minimum requirements to work this plugin?</p>
                        <p class="answer">Minimum Requirements:<br/>
                        <ol>
                            <li>PHP 5.2.3 or Above</li>
                            <li>WordPress 4.5 or Above</li>
                            <li>WooCommerce Plugin Installed and Activated.</li>
                        </ol>
                        </p>
                    </div>
                    <div class="faqs-block">
                        <p class="question">Does this plugin work without WooCommerce plugin installed?</p>
                        <p class="answer">No, this plugin is an addon to WooCommerce plugin.</p>
                    </div>
                    <div class="faqs-block">
                        <p class="question">How can I add 360 degree images of product?</p>
                        <p class="answer">You can add 360 degree images of your product on product page itself. Scroll down to Product 360&#176; View meta-box, there you will find a button saying "Add 360&#176; images".</p>
                    </div>
                    <div class="faqs-block">
                        <p class="question">Can I change product specific background color?</p>
                        <p class="answer">Yes, you can change product specific background color. In Product 360&#176; View meta-box, under Add 360&#176; images button there is a check-box saying "Custom background color", just check that check-box and you can change the product specific background color.</p>
                    </div>
                    <div class="faqs-block">
                        <p class="question">Why my Custom 360&#176; icon added twice in the media gallery?</p>
                        <p class="answer">Some free/premium themes have different CSS classes and HTML structure to show the product gallery. In those themes, sometimes, it gets hard to detect the click on custom 360&#176; icon. So, that is why this plugin uploads the custom 360&#176; icon again into media gallery with a particular name.</p>
                    </div>
                    <div class="faqs-block" id="icon_not_showing">
                        <p class="question">360&#176; icon is not showing up on front-end and/or in plugin settings page?</p>
                        <p class="answer">This issue occurs when the 360&#176; icon images get removed from media gallery. To fix this issue, just, deactivate the plugin and then reactivate it again.</p>
                    </div>
                    <div class="faqs-block">
                        <p class="question">360&#176; images are distorted in mobile view. What to do?</p>
                        <p class="answer">I suggest users to use images of maximum 600px width in 'original' mode, but, if you have big ratio images( more than 800px width ), you can use the responsive mode. And please try to make images just showing the product, sometimes peoples have images in which the background size is too big than product image, if this is the case with you, please try to remove extra background space from image and focus more on product image.</p>
                    </div>
                    <div class="faqs-block">
                        <p class="question">Where can I contact the plugin author?</p>
                        <p class="answer">You can simply send email to <a href='mailto:info@superrishi.com' target="_top">info@superrishi.com</a> or can visit the website <a href='http://superrishi.com' target='_blank'>SUPERRISHI.COM</a>.</p>
                    </div>
                </div>
            </div>
            <div id="submit_query" class="help-center-data">
                <iframe frameborder="0" style="height:500px;width:99%;border:none;" src='https://forms.zohopublic.com/info1990/form/ContactForm/formperma/MuVnUgKvHjlKp316GpGCEZWvcFrswa7oHJ465dZ1gP8'></iframe>
            </div>
        </div>
    </div>
</div>
<ul class="sr_footer">
    <li>
        <a target="_blank" href="https://wordpress.org/support/plugin/sr-product-360o-view/reviews/#new-post"><?= __('PLEASE RATE PLUGIN HERE.', 'sr-product-360-view'); ?></a>
    </li>
    <li>
        <a target="_blank" href="http://superrishi.com/donate-to-support/"><?= __('DONATE TO SUPPORT REGULAR UPDATES.', 'sr-product-360-view'); ?></a>
    </li>
</ul>
<div class="popup__overlay">
    <div class="popup" id="popupVid">
        <a href="#" class="popup__close">X</a>
        <iframe src="https://www.youtube.com/embed/knbjmbp5WeA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
</div>
<script>
    jQuery('#colorSelector').ColorPicker({
        color: '#0000ff',
        onShow: function (colpkr) {
            jQuery(colpkr).fadeIn(500);
            return false;
        },
        onHide: function (colpkr) {
            jQuery(colpkr).fadeOut(500);
            return false;
        },
        onChange: function (hsb, hex, rgb) {
            jQuery('#colorSelector div').css('backgroundColor', '#' + hex);
            jQuery('#bgcolor').val('#' + hex);
        }
    });
    var p = jQuery(".popup__overlay");

    jQuery("#video_tutorial img").click(function () {
        p.css("display", "block");
    });
    p.click(function (event) {
        e = event || window.event;
        if (e.target == this) {
            jQuery(p).css("display", "none");
        }
    });
    jQuery(".popup__close").click(function () {
        p.css("display", "none");
    });

//video popup
    function toggleVideo(state) {
        // if state == 'hide', hide. Else: show video
        var div = document.getElementById("popupVid");
        var iframe = div.getElementsByTagName("iframe")[0].contentWindow;
        //div.style.display = state == 'hide' ? 'none' : '';
        func = state == "hide" ? "pauseVideo" : "playVideo";
        iframe.postMessage(
                '{"event":"command","func":"' + func + '","args":""}',
                "*"
                );
    }

    jQuery("#video_tutorial img").click(function () {
        p.css("visibility", "visible").css("opacity", "1");
    });

    p.click(function (event) {
        e = event || window.event;
        if (e.target == this) {
            jQuery(p)
                    .css("visibility", "hidden")
                    .css("opacity", "0");
            toggleVideo("hide");
        }
    });

    jQuery(".popup__close").click(function () {
        p.css("visibility", "hidden").css("opacity", "0");
        toggleVideo("hide");
    });
    jQuery(".help-center-menu li").on('click', function () {
        jQuery(".help-center-menu li").removeClass('active');
        jQuery(this).addClass('active');
        var target = jQuery(this).data('target');
        jQuery(".help-center-data").removeClass('active');
        jQuery(target).addClass('active');
    });
//Choose icon
    jQuery(function ($) {
        var file_frame;
        $(document).on("click", "button.choose-icon", function (event) {
            event.preventDefault();
            if (file_frame) {
                file_frame.close()
            }
            file_frame = wp.media.frames.file_frame = wp.media({
                title: $(this).data("uploader-title"),
                button: {
                    text: $(this).data("uploader-button-text"),
                },
                multiple: false
            });
            file_frame.on("select", function () {
                selection = file_frame.state().get("selection");
                selection.map(function (attachment, i) {
                    attachment = attachment.toJSON();
                    $("#sr-360-icon-3, #sr-360-icon-3 + label").removeClass('hidden');
                    $(".sr-360-icon-3").css('background-image', 'url(' + attachment.url + ')');
                    $("#sr_360_icon_custom_url").val(attachment.url);
                    $("#sr_360_icon_custom").val(attachment.id);
                })
            });
            file_frame.open()
        });
    });
//Choose icon end
    jQuery("input[name='loading_placeholder']").on('change', function () {
        jQuery(this).blur();
        if (jQuery("#loading_placeholder_text").is(":checked")) {
            jQuery("#loading_placeholder_custom_text").removeClass('readonly').removeAttr('readonly').focus();
        } else {
            jQuery("#loading_placeholder_custom_text").addClass('readonly').attr('readonly', 'readonly');
        }
    });
    jQuery("#animation").on('change', function () {
        if (jQuery("#animation").is(":checked")) {
            jQuery(".depend-animation").closest('li').removeClass('sr-disabled');
        } else {
            jQuery(".depend-animation").closest('li').addClass('sr-disabled');
            jQuery("#animation_reverse").attr('checked', false).prop('checked', false);
        }
    });
</script>