<?php
    if ( ! is_active_sidebar( 'postfooter' ) || in_category( array(1008,1009,1010,1011,1012 ) ))
        return;
?>
 
<div id="postfooter-widget">
    <div class="clearfix container">
    <?php
        $postfooter_sidebars = array( 'postfooter' );
        foreach ( $postfooter_sidebars as $key => $postfooter_sidebar ){
            if ( is_active_sidebar( $postfooter_sidebar ) ) {
                echo '<div class="open-subscription' . (  2 == $key ? ' last' : '' ) . '">';
                dynamic_sidebar( $postfooter_sidebar );
                echo '</div> <!-- end .postfooter-widget -->';
            }
        }
    ?>
    </div>
</div>