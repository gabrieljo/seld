<?php $this->load->view('inc/n_header'); ?>

<div class="container market-wrapper">	
    <div id="content" class="col-sm-10 col-sm-offset-1">
        <h1><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</h1>

        <table class="table table-condensed table-hover">
            <thead>
                <th width="30">S.N.</th>
                <th>Title</th>
                <th>Author</th>
                <th width="120" class="text-center">Amount</th>
                <th width="120" class="text-right">&nbsp;</th>
            </thead>
            <tbody>
                <?php
                $sn = 0;
                foreach ($cart as $item):
                ?>
                <tr>
                    <td><?=++$sn?></td>
                    <td><?=anchor('market/design/' . $item['id'], $item['name'])?></td>
                    <td><?=$item['author']?></td>
                    <td class="text-right"><strong>₩ <?=number_format($item['price'])?></strong></td>
                    <td class="text-right"><?=anchor('market/removeProduct/' . $item['rowid'], '<span class="glyphicon glyphicon-trash"></span> Delete', array('class'=>'btn btn-xs btn-danger'))?></td>
                </tr>
                <?php
                endforeach;
                ?>
            </tbody>
            <tfoot>
            <td class="text-right" colspan="4">
                <?php
                if ($amount <= 0){
                    echo '<div class="text-center text-danger">';
                    echo '<br /><strong>Your cart is empty!</strong><br />';
                    echo anchor('market', '<span class="glyphicon glyphicon-list"></span> Start Shopping', array('class'=>'btn btn-success'));
                    echo '</div>';
                }
                else{
                    echo '<h4>Total: <strong>₩ ' . number_format($amount) . '</strong></h4>';
                    echo anchor('market/checkout', 'Checkout', array('class'=>'btn btn-primary btn-sm'));
                }
                ?>
            </td>
            <td></td>
            </tfoot>
        </table>
    </div>
</div>
<?php $this->load->view('inc/n_footer'); ?>