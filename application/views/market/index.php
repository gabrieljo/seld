<?php $this->load->view('inc/n_header'); ?>// Loads

<div class="container market-wrapper">
	<div id="column-left" class="col-sm-3 hidden-xs">
	    <div class="list-group">
      <?php  foreach($categories as $category){ ?>
          <a href="#" class="list-group-item"><?php echo $category->cat_name?></a>
      <?php  } ?>
	     <!--  <a href="#" class="list-group-item">Sugar Baby (0)</a>
        <a href="#" class="list-group-item active">Natural Health (84)</a>
        <a href="#" class="list-group-item">Pharmacy (63)</a>
        <a href="#" class="list-group-item">Mother and Baby (2)</a>
        <a href="#" class="list-group-item">Beauty (4)</a>
        <a href="#" class="list-group-item">NZ Products (14)</a> -->
	    </div>
  </div>
  <div id="content" class="col-sm-9">
    <div class="sub-title">SELD 마켓</div>
    <hr>
    <div class="row">
      <div class="col-sm-10">
        <ul id="sub-categories">
          <?php
            foreach($sub_categories as $sub){
              ?>
              <li><a href="#"><?php echo $sub->cat_name?></a></li>
              <?php
            }
          ?>
        </ul>
      </div>
    </div>
      <hr>
     
      <div class="row">
        <div class="col-md-4">
          <div class="btn-group hidden-xs">
            <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="" data-original-title="List"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="" data-original-title="Grid"><i class="fa fa-th"></i></button>
          </div>
        </div>
        <div class="col-md-2 text-right">
          <label class="control-label" for="input-sort">Sort By:</label>
        </div>
        <div class="col-md-3 text-right">
          <select id="input-sort" class="form-control" onchange="location = this.value;">
              <option value="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;sort=p.sort_order&amp;order=ASC" selected="selected">Default</option>
              <option value="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;sort=pd.name&amp;order=ASC">Name (A - Z)</option>
              <option value="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;sort=pd.name&amp;order=DESC">Name (Z - A)</option>
              <option value="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;sort=p.price&amp;order=ASC">Price (Low &gt; High)</option>
              <option value="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;sort=p.price&amp;order=DESC">Price (High &gt; Low)</option>
              <option value="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;sort=rating&amp;order=DESC">Rating (Highest)</option>
              <option value="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;sort=rating&amp;order=ASC">Rating (Lowest)</option>
              <option value="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;sort=p.model&amp;order=ASC">Model (A - Z)</option>
              <option value="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;sort=p.model&amp;order=DESC">Model (Z - A)</option>
          </select>
        </div>
        <div class="col-md-1 text-right">
          <label class="control-label" for="input-limit">Show:</label>
        </div>
        <div class="col-md-2 text-right">
          <select id="input-limit" class="form-control" onchange="location = this.value;">
            <option value="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;limit=15" selected="selected">15</option>
                <option value="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;limit=25">25</option>
                <option value="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;limit=50">50</option>
                <option value="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;limit=75">75</option>
                <option value="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;limit=100">100</option>
          </select>
        </div>
      </div>
      <br>
      <div class="row">
          <?php foreach($items as $item){?>
            <div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <div class="product-thumb">
                <div class="image"><a href="https://healthmarket.co.nz/clinicians-brain-boost-cognizin"><img src="https://healthmarket.co.nz/image/cache/catalog/products/clinicians/166-228x228.png" alt="<?php echo $item->pr_title?>" title="<?php echo $item->pr_title?>" class=""></a></div>
                <div>
                  <div class="caption">
                    <h4><a href="#;.m"><?php echo $item->pr_title?></a></h4>
                    <p><?php echo $item->pr_description?></p>
                    <p class="price"><span class="price-new" style="text-decoration:line-through;"><?php echo $item->mk_org_price ?></span> <span class="price-old">$<?php echo $item->mk_price ?></span></p></div>
                    <div class="button-group">
                      <button type="button"><i class="fa fa-pencil-square-o"></i> <span class="hidden-xs hidden-sm hidden-md">디자인 하기</span></button>
                      <button type="button" title="" ><i class="glyphicon glyphicon-eye-open"></i> 즐겨찾기</button>
                    </div>
                </div>
              </div>
            </div>
          <?php }?>
        </div>
        <div class="clearfix visible-lg"></div>
      <div class="row">
        <div class="col-sm-6 text-left"><ul class="pagination"><li class="active"><span>1</span></li><li><a href="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;page=2">2</a></li><li><a href="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;page=2">&gt;</a></li><li><a href="https://healthmarket.co.nz/index.php?route=product/category&amp;path=71_72&amp;page=2">&gt;|</a></li></ul></div>
        <div class="col-sm-6 text-right">Showing 1 to 15 of <?=count($items)?> (2 Pages)</div>
      </div>
  </div>
</div>

<?php $this->load->view('inc/n_footer'); ?>