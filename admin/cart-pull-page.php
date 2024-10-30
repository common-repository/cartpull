<?php //echo get_option('enable_checkoput_registration'); ?>
<div class="wrap">
    <h1><?php _e('HookMax','cart-pull')?></h1>
    <p><?php _e('Automatically send reminder emails to customers who 
    have abondoned thair cart and recover lost sales.', 'cart-pull') ?></p></p>
    <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
        <span class="nav-tab nav-tab-active"><?php _e('Settings','cart-pull') ?></span>		
    </h2>
            
    <form method="post" action="options.php">
        <?php settings_fields( 'cart-pull-settings-group' ); ?>
        <?php do_settings_sections( 'cart-pull-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php _e('Connection status','cart-pull')?></th>
            <td>
                <?php 
                    $url      = CART_PULL_URL.'/stores/create';
                    $key      = HookMax::get_instance()->key;
                    $home_url = home_url();
                    $name     = get_bloginfo( 'name' );
                ?>
                <?php if( get_option('cart_pull_verified') == true ): ?>
                <span style="color:#2b9e01; margin-Top:7px; margin-right:7px; display:inline-block">&#10004;</span>
                <?php else: ?>
                <span style="color:#ed0738; margin-Top:7px; margin-right:7px; display:inline-block">&#10060;</span>
                <?php endif ?> 
                <a href="<?php echo $url.'?key='.$key.'&home_url='.$home_url.'&name='.$name ?>" target="_blank" class="connect button button-primary button-large">
                    <?php _e('Connect to HookMax', 'cart-pull')?> 
                </a>
            </td>
            </tr>
            
        </table>
        
    </form>
</div>



