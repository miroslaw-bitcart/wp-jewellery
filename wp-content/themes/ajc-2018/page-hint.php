<?php get_header(); ?>

<div class="wrapper" data-columns>

<h2>Drop a Hint</h2>

<div class="half left">
    <?php gravity_form(6, false, false, false, '', true); ?>
    <script>
    jQuery(function() {
        jQuery('#input_6_1').keyup(function() {
            jQuery('#preview-recipient-name').text($(this).val());
        });
        jQuery('#input_6_5').keyup(function() {
            jQuery('#preview-your-name').text($(this).val());
        });
    });
    </script>
</div>

<div class="half left">
    <p>Dear <span id="preview-recipient-name"></span>,</p>
    <p>We thought you'd like to know that <span id="preview-your-name"></span> has been admiring our <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a> for some time now.</p>
    <p>Don't shoot the messenger...</p>
</div>

<?php get_footer(); ?>