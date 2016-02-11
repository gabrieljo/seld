<?php $this->load->view('inc/n_header'); ?>

<div class="container market-wrapper">	
    <div id="content" class="col-sm-10 col-sm-offset-1">
        <h1><span class="glyphicon glyphicon-shopping-cart"></span> Checkout</h1>        
        <div class="text-center">
        <?php
        if ($amount <= 0){
            echo '<div class="text-center text-danger">';
            echo 'Your cart is empty!<br />';
            echo anchor('market', '<span class="glyphicon glyphicon-list"></span> Start Shopping', array('class'=>'btn btn-success'));
            echo '</div>';
        }
        else{
        ?>
            <h4>Total Amount: <strong>₩ <?=number_format($amount)?></strong> <small class='pull-right text-info'><?=$total?> items</small></h4>
            <?php
            if ($client->cl_id == 0){
                // login to continue.
                echo anchor('p/login/' . base64_encode(current_url()), '<span class="glyphicon glyphicon-user"></span> Login to continue', array('class'=>'btn btn-sm btn-primary'));
            }
            else{
                echo 'Email Address: <strong>' . $client->cl_email . '</strong>';
                echo anchor('market/payment', inc('btn-paypal.png', array('style'=>'height:48px;')));
            }
            ?>
        <?php
        } // endif.
        ?>
        </div>
    </div>
</div>
<?php $this->load->view('inc/n_footer'); ?>