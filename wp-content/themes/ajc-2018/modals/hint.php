<div class="modal fade" id="hint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content hint">

            <div class="modal-header">
                <a class="close ion-android-close" data-dismiss="modal"></a>
                <h2>Drop a Hint</h2>
            </div>

            <div class="modal-body">

                <div class="col">
                    <div class="modal-form clearfix">
                        <?php gravity_form(6, false, false, false, '', true); ?>
                    </div>
                </div>

                <div class="col preview">
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
                    <p>Dear <span id="preview-recipient-name"></span>,</p>
                    <p>We thought you'd like to know that <span id="preview-your-name"></span> has been eyeing up our <strong><?php the_title(); ?></strong> for some time now, and has requested us to bring this to your attention. Don't shoot the messenger...</p>
                    <p>Kind regards,<br>The Antique Jewellery Company</p>
                </div>

            </div>           
        </div>
    </div>
</div>