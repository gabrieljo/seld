<?php $this->load->view('inc/n_header'); ?>

<div class="container market-wrapper">
	<div id="column-left" class="col-sm-3 hidden-xs">
	    <div class="list-group">
        <?php
        $cls = '';
        foreach($categories as $item){
          $cls = $item->cat_id == $sub_id ? 'active' : '';
          echo anchor('market/page/' . $order_by . '/1/' . $item->cat_id . '/' . url_title($item->cat_name_en) , $item->cat_name, array('class'=>'list-group-item ' .$cls ));
        } 
        ?>
	    </div>
    </div>
    <div id="content" class="col-sm-9">
        <div class="sub-title">SELD 마켓</div>
        <hr>
        <div class="row">
          <div class="col-sm-10">
            <ul id="sub-categories">
              <?php
                $cls = '';
                foreach($sub_categories->result() as $item){
                  $cls = $item->cat_id == $cat_id ? 'background:#337AB7;color:#fff;' : '';
                  echo '<li>' . anchor('market/page/' . $order_by . '/1/' . $item->cat_id . '/' . url_title($item->cat_name_en), $item->cat_name, array('style'=>$cls)) . '</li>';
                }
              ?>
            </ul>
          </div>
        </div>
        <hr>         
        <div class="row">
            <div class="col-md-4">
              <div class="btn-group hidden-xs">
               <!--  <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="" data-original-title="List"><i class="fa fa-th-list"></i></button>
                <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="" data-original-title="Grid"><i class="fa fa-th"></i></button> -->
              </div>
            </div>
            <div class="col-md-2 col-sm-offset-3 text-right">
              <label class="control-label" for="input-sort">Sort By:</label>
            </div>
            <div class="col-md-3 text-right">
              <?php
              $options = array(
                            base_url() . 'market/page/name-asc'    => 'Name (A - Z)',
                            base_url() . 'market/page/name-desc'   => 'Name (Z - A)',
                            base_url() . 'market/page/date-asc'    => 'Date (Newest First)',
                            base_url() . 'market/page/date-desc'   => 'Date (Oldest First)',
                            base_url() . 'market/page/price-asc'   => 'Price (Low &gt; High)',
                            base_url() . 'market/page/price-desc'  => 'Price (High &gt; Low)'
                          );
              echo form_dropdown('input-sort', $options, base_url() . 'market/page/' . $order_by, 'class="form-control" onchange="location=this.value"');
              ?>
            </div>
        </div>
        <br> 
        <div class="row">
            <?php 
            $tb = base64_encode(current_url());

            foreach($items->result() as $item){
                // get price
                $price      = $item->pr_mk_orig_price;
                $prc_spl    = $item->pr_mk_price;

                $display    = '<strong>₩' . number_format($price) . '</strong>';
                if ($prc_spl > 0 && $prc_spl < $price){
                    $display = '<i class="price-new" style="text-decoration:line-through">₩' . number_format($price) . '</i> <strong>₩' . number_format($prc_spl) . '</strong>';
                }
            ?>
            <div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="product-thumb">
                    <div class="image">
                        <!-- <a href="https://healthmarket.co.nz/clinicians-brain-boost-cognizin"><img src="https://healthmarket.co.nz/image/cache/catalog/products/clinicians/166-228x228.png" alt="<?php echo $item->pr_title?>" title="<?php echo $item->pr_title?>" class=""></a> -->
                        <?=inc('../products/' . $item->pr_uid . '/design/thumbs/page-1.png')?>
                    </div>
                    <div>
                        <div class="caption">
                            <h4><?=anchor('market/design/' . $item->pr_uid . '/' . url_title($item->pr_title), $item->pr_title)?></h4>
                            <p><?php echo $item->pr_description?></p>
                            <p class="price"><?=$display?></p>
                        </div>
                        <div class="button-group">
                            <button type="button">
                                <?=anchor('market/design/' . $item->pr_uid . '/' . url_title($item->pr_title), '<i class="fa fa-pencil-square-o"></i> 디자인 하기', array('class'=>'hidden-xs hidden-sm hidden-md'))?>
                            </button>
                            <button type="button" title="">
                                <?php
                                $cls = $item->fav_status == 'published' ? 'text-danger' : '';
                                echo anchor('market/favourite/' . $item->pr_uid . '/' . $tb, '<i class="glyphicon glyphicon-eye-open ' . $cls . '"></i> 즐겨찾기', array('class'=>$cls));
                                ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
            } // ENDFOREACH.

            if ($total == 0){
              echo '<h1 class="text-danger">No designs available in selected category!</h1>';
            }
            ?>

        </div>
        <div class="clearfix visible-lg"></div>
        <div class="row">
            <div class="col-sm-6 text-left">
                    <?=$pagination?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('inc/n_footer'); ?>