<?php $product = $template_args['product']; ?>

<table align="left" border="0" cellpadding="0" cellspacing="0" width="false" style="border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;">
    <tbody>
        <tr>
            <td style="padding:0 9px 9px 9px;border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace: 0pt;" align="center" valign="top">
                <a href="<?php echo $product->get_permalink(); ?>">
                <img alt="<?php echo $product->get_title(); ?>" src="<?php echo $product->get_thumbnail('product-large'); ?>"/>
                </a>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 9px 0 9px;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;color: #505050;font-family: georgia,serif;font-size:14px;line-height: 150%;text-align: left;" valign="top" width="564">
                <div style="text-align: center;padding-bottom:24px;">
                    <?php echo $product->get_title(); ?> (<?php echo $product->get_sku(); ?>)<br /><?php echo $product->get_period(); ?> / &pound;<?php echo $product->get_price(); ?>
                </div>
            </td>
        </tr>
    </tbody>
</table>