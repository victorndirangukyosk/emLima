<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-body">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?= $text_general ?></a></li>
            <li><a href="#tab-vendor" data-toggle="tab"><?= $text_vendor ?></a></li>
            <?php if($package){ ?>
            <li><a href="#tab-package" data-toggle="tab"><?= $text_package ?></a></li>
            <?php } ?>
            <li><a href="#tab-connect" data-toggle="tab"><?= $tab_bank_details ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active in" id="tab-general">          
                <table class="table table-bordered">
                    <tr>
                        <td><b><?= $column_username ?></b></td>
                        <td><?= $user['username'] ?></td>
                    </tr>
                    <tr>
                        <td><b><?= $column_firstname ?></b></td>
                        <td><?= $user['firstname'] ?></td>
                    </tr>
                    <tr>
                        <td><b><?= $column_lastname ?></b></td>
                        <td><?= $user['lastname'] ?></td>
                    </tr>
                    <tr>
                        <td><b><?= $column_email ?></b></td>
                        <td><?= $user['email'] ?></td>
                    </tr>
                    <tr>
                        <td><b><?= $column_ip_address ?></b></td>
                        <td><?= $user['ip'] ?></td>
                    </tr>
                    <tr>
                        <td><b><?= $column_date ?></b></td>
                        <td><?= date('d-m-Y', strtotime($user['date_added'])) ?></td>
                    </tr>
                </table>
            </div>
              
              
            <div class="tab-pane" id="tab-connect">
                <table class="table table-bordered">
                    <tr>
                        <td><b>Bank A/c No</b></td>
                        <td><?php echo $bank_account_number; ?></td>
                    </tr>
                    <tr>
                        <td><b>Bank Account Name</b></td>
                        <td><?php echo $bank_account_name; ?></td>
                    </tr>
                    <tr>
                        <td><b>Bank Name</b></td>
                        <td><?php echo $bank_name; ?></td>
                    </tr>
                    <tr>
                        <td><b>Branch Name</b></td>
                        <td><?php echo $bank_branch_name; ?></td>
                    </tr>
                    <tr>
                        <td><b>Account Type</b></td>
                        <td><?= $bank_account_type ?></td>
                    </tr>
                </table>
                
            </div>

            <!-- connect end -->

            <div class="tab-pane" id="tab-vendor">
                <table class="table table-bordered">

                    <?php if(strtotime($user['free_to']) <= time()){ ?> 
                    <tr>
                        <td><b><?= $column_commision ?></b></td>
                        <td><?= $user['commision'] ?>%</td>
                    </tr>
                    <?php }else{ ?>
                    <tr>
                        <td><b><?= $column_account ?></b></td>
                        <td><?= date('d-m-Y', strtotime($user['free_to'])) ?></td>
                    </tr>
                    <?php } ?>
                    
                    <tr>
                        <td><b><?= $column_business ?></b></td>
                        <td><?= $user['business'] ?></td>
                    </tr>

                    <tr>
                        <td><b><?= $column_type ?></b></td>
                        <td><?= $user['type'] ?></td>
                    </tr>

                    <tr>
                        <td><b><?= $column_tin_no ?></b></td>
                        <td><?= $user['tin_no'] ?></td>
                    </tr>

                    <tr>
                        <td><b><?= $column_mobile ?></b></td>
                        <td><?= $user['mobile'] ?></td>
                    </tr>

                    <tr>
                        <td><b><?= $column_telephone ?></b></td>
                        <td><?= $user['telephone'] ?></td>
                    </tr>

                    <tr>
                        <td><b><?= $column_city ?></b></td>
                        <td><?= $user['city'] ?></td>
                    </tr>

                    <tr>
                        <td><b><?= $column_address ?></b></td>
                        <td><?= $user['address'] ?></td>
                    </tr>

                </table>
            </div>  
            
            <?php if($package){ ?>
            <div class="tab-pane" id="tab-package">
                <table class="table table-bordered">
                    <tr>
                        <td><b><?= $column_name ?></b></td>
                        <td><?= $package['name'] ?></td>
                    </tr>
                    <tr>
                        <td><b><?= $column_priority ?></b></td>
                        <td><?= $package['priority'] ?></td>
                    </tr>
                    <tr>
                        <td><b><?= $column_activation_date ?></b></td>
                        <td><?= date('d-m-Y', strtotime($package['date_start'])) ?></td>
                    </tr>
                    <tr>
                        <td><b><?= $column_active_upto_date ?></b></td>
                        <td><?= date('d-m-Y', strtotime($package['date_end'])) ?></td>
                    </tr>
                </table>
            </div>
            <?php } ?>
            
          </div><!-- END tab-content -->
      </div><!-- END .panel-body -->
    </div><!-- END panel -->
  </div>
</div>
<?php echo $footer; ?>
          