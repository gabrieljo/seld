<?php
$this->load->view('inc/n_header');

// get design options.
$design_keys = array();
foreach($d_options->result() as $opt){
    $key = $opt->d_op_dep_id == 0 ? $opt->d_op_name : $opt->d_op_name . '-' . $opt->d_op_id;
    $design_keys[$key] = $opt->d_op_title;
}
?>

<div class="container market-wrapper">
	
  <div id="content" class="col-sm-9">
    <br>
    <ol class="breadcrumb">
      <li><?=anchor('', 'Home')?></li>
      <li><?=anchor('market', 'Market')?></li>
      <li class="active"><?=ucfirst($product->pr_title)?></li>
    </ol>
    <div class="sub-title">SELD 마켓</div>
    <hr>
    <div class="row">
        <div class="col-sm-12">
            <div class="design-preview text-center">
                <?php
                $src = $product->pr_src == 'seld' ? 'design/thumbs/page-1.png' : 'preview.' . $product->pr_preview;
                echo inc('../products/' . $product->pr_uid . '/' . $src, array('style'=>'max-width:80%;height:auto; max-height:180px;'));
                ?>
            </div>
            <hr>
            <h2><?=ucfirst($product->pr_title)?></h2>
            <div class="product_desc">
                <?=nl2br($product->pr_mk_description)?>
            </div>
        </div>
    </div>
  </div>
  <div id="column-left" class="col-sm-3 hidden-xs">
        <br>
        <div class="panel panel-warning">
            <div class="panel-heading"><strong>Options</strong></div>
            <div class="panel-body">
                <?php
                $options = unserialize($product->pr_options);
                //new dBug($options);exit;
                foreach ($options as $k=>$v){
                    echo '<strong>' . $design_keys[$k] . '</strong> : ' . ucfirst($v) . '<br />';
                }
                ?>
            </div>
        </div>

        <?php
        // get price
        $price      = $product->pr_mk_orig_price;
        $prc_spl    = $product->pr_mk_price;

        $old_prc    = '';
        $display    = '<h3>₩ ' . number_format($price) . '</h3>';

        if ($prc_spl > 0 && $prc_spl < $price){
            $old_prc = '<i class="price-new" style="text-decoration:line-through">₩' . number_format($price) . '</i>';
            $display = '<h3>₩' . number_format($prc_spl) . '</h3>';
        }

        echo $old_prc . $display;

        /**
         * 5 star ratings. 
         */
        $ratings        = '';
        $rating_value   = floor($rating);

        for ($i=1; $i<=5; $i++){
            $val      = $i <= $rating_value ? '' : '-empty';
            $ratings .= '<span class="glyphicon glyphicon-star' . $val . '" data-ref="' . $i . '"></span>';
        }

        echo anchor('market/addToCart/' . $product->pr_uid, '<span class="glyphicon glyphicon-shopping-cart"></span> Add to Cart.', array('class'=>'btn btn-lg btn-primary', 'style'=>'display:block;')) . '<div style="margin-top:10px;"></div>';
        ?>
        <hr>
        <div class="design-ratings available" data-default="<?=$rating_value?>"><?=$ratings?></div>
        <div class="design-ratings-loading hidden"><?=inc('spinner.gif')?></div>

        <?php

        /**
         * Product Favourites..
         */
        $cls    = 'btn-info';
        $title  = 'Click to add to favorites!';
        $text   = 'Add to Favourites';
        if ($favourite && $favourite->fav_status == 'published'){

            $text   = 'Favourite';
            $cls    = 'btn-warning';
            $title  = 'Click to remove from your favourites.';
        }
        echo anchor('market/favourite/' . $product->pr_uid . '/' . base64_encode(current_url()), '<span class="glyphicon glyphicon-heart"></span> ' . $text, array('class'=>'btn  btn-sm pull-right ' . $cls, 'title'=>$title));
        ?>
  </div>
</div>
<?php
if ($client_id > 0){
?>
<script>
var design = {
    config: {
        ratingURL : ''
    },
    showRatings: function(){

        // reset stars first.
        $('.design-ratings span').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
        var to = $(this).attr('data-ref');
        for (var i=1; i<=to; i++){
            $('.design-ratings span:eq(' + (i-1) + ')').removeClass('glyphicon-star-empty').addClass('glyphicon-star');
        }
    },
    resetRatings: function(){
        
        //
        $('.design-ratings span').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
        var to = $('.design-ratings').attr('data-default');
        for (var i=1; i<=to; i++){
            $('.design-ratings span:eq(' + (i-1) + ')').removeClass('glyphicon-star-empty').addClass('glyphicon-star');
        }
    },
    setRating: function(){

        var rating = $(this).attr('data-ref');
        $('.design-ratings').addClass('hidden');
        $('.design-ratings-loading').removeClass('hidden');
        $.ajax({
            type: 'post',
            data: 'rating=' + rating,
            url: design.config.ratingURL,
            success: function(data){
                $('.design-ratings').attr('data-default', rating).removeClass('hidden');
                $('.design-ratings-loading').addClass('hidden');
            }, 
            error: function(){
                alert('Unable to set ratings! try again later.');
                $('.design-ratings').attr('data-default', rating).removeClass('hidden');
                $('.design-ratings-loading').addClass('hidden');
            }
        });
    },
    init: function(){
        $('.design-ratings.available span').mouseover(design.showRatings);
        $('.design-ratings.available span').mouseout(design.resetRatings);
        $('.design-ratings.available span').click(design.setRating);
    }
};

design.config.ratingURL = '<?=base_url() . "market/rating/" . $product->pr_uid . "/1/" . base64_encode(current_url())?>';
$(design.init);
</script>
<?php
}
?>
<?php $this->load->view('inc/n_footer'); ?>