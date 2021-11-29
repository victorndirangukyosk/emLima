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
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <td><b><?= $column_name ?></b></td>
                            <td><?= $firstname.' '.$lastname ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $column_username ?></b></td>
                            <td><?= $username ?></td>
                        </tr>
                        <!-- <tr>
                            <td><b><?= $column_password ?></b></td>
                            <td><?= $password ?></td>
                        </tr> -->
                        <tr>
                            <td><b><?= $column_email ?></b></td>
                            <td><?= $email ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $column_business ?></b></td>
                            <td><?= $business ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $column_type ?></b></td>
                            <td><?= $type ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $column_tin_no ?></b></td>
                            <td><?= $tin_no ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $column_mobile ?></b></td>
                            <td><?= $mobile ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $column_telephone ?></b></td>
                            <td><?= $telephone ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $column_city ?></b></td>
                            <td><?= $city ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $column_address ?></b></td>
                            <td><?= $address ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $column_store_name ?></b></td>
                            <td><?= $store_name ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $column_about_us ?></b></td>
                            <td><?= $about_us ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $column_date ?></b></td>
                            <td><?= date('d-m-Y', strtotime($date_added)) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>