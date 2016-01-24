<div class="mypage-profile">
	<form action="#" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset id="account">
          <legend>사용자 기본정보</legend>
          <div class="form-group required" style="display: none;">
            <label class="col-sm-2 control-label">Customer Group</label>
            <div class="col-sm-10">
            <div class="radio">
                <label>
                  <input type="radio" name="customer_group_id" value="1" checked="checked">
                  Default</label>
              </div>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-firstname">First Name</label>
            <div class="col-sm-10">
              <input type="text" name="firstname" value="" placeholder="First Name" id="input-firstname" class="form-control">
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-lastname">Last Name</label>
            <div class="col-sm-10">
              <input type="text" name="lastname" value="" placeholder="Last Name" id="input-lastname" class="form-control">
                          </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-email">E-Mail</label>
            <div class="col-sm-10">
              <input type="email" name="email" value="" placeholder="E-Mail" id="input-email" class="form-control">
                          </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-telephone">User Name</label>
            <div class="col-sm-10">
              <input type="text" name="username" value="" placeholder="User Name" id="input-username" class="form-control">
            </div>
          </div>
        </fieldset>
        <fieldset id="address">
          <legend>배송지 정보</legend>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-company">Company</label>
            <div class="col-sm-10">
              <input type="text" name="company" value="" placeholder="Company" id="input-company" class="form-control">
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-address-1">Address 1</label>
            <div class="col-sm-10">
              <input type="text" name="address_1" value="" placeholder="Address 1" id="input-address-1" class="form-control">
          	</div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-address-2">Address 2</label>
            <div class="col-sm-10">
              <input type="text" name="address_2" value="" placeholder="Address 2" id="input-address-2" class="form-control">
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-city">City</label>
            <div class="col-sm-10">
              <input type="text" name="city" value="" placeholder="City" id="input-city" class="form-control">
                          </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-postcode">Post Code</label>
            <div class="col-sm-10">
              <input type="text" name="postcode" value="" placeholder="Post Code" id="input-postcode" class="form-control">
                          </div>
          </div>
          
        </fieldset>
        <fieldset>
          <legend>비밀번호</legend>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password">Password</label>
            <div class="col-sm-10">
              <input type="password" name="password" value="" placeholder="Password" id="input-password" class="form-control">
                          </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-confirm">Password Confirm</label>
            <div class="col-sm-10">
              <input type="password" name="confirm" value="" placeholder="Password Confirm" id="input-confirm" class="form-control">
                          </div>
          </div>
        </fieldset>
        <div class="buttons">
          <div class="pull-right">I have read and agree to the <a href="https://healthmarket.co.nz/index.php?route=information/information/agree&amp;information_id=3" class="agree"><b>Privacy Policy</b></a><input type="checkbox" name="agree" value="1">
                        &nbsp;
            <input type="submit" value="저장" class="btn btn-primary">
          </div>
        </div>
    </form>
</div>