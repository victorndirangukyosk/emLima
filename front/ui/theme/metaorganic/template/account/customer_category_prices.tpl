<?php echo $header;?>

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>

        </div>
    </div>
    <div class="container-fluid">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i>Category Prices</h3>

            </div>
            <div class="panel-body">

                <form action="" method="post" enctype="multipart/form-data" id="form-recentorders">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td class="text-left">Product Store Id</td>
                                    <td class="text-left">Name</td>
                                    <td class="text-left">UOM</td>
                                    <td class="text-left">Category Name</td>
                                    <td class="text-left">Price</td>
                                    <td class="text-left">Special Price</td>
                                    <td class="text-left">Category Price</td>

                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($products) { ?>
                                <?php foreach ($products as $pro) { ?>
                                <?php foreach ($pro['variations'] as $variation) { ?>
                                <tr>
                                    <td class="text-left"><?php echo $variation['variation_id']; ?></td>
                                    <td class="text-left"><?php echo $variation['name']; ?></td>
                                    <td class="text-left"><?php echo $variation['unit']; ?></td>
                                    <td class="text-left"><?php echo $variation['category_name']; ?></td>
                                    <td class="text-left"><?php echo $variation['price']; ?></td>
                                    <td class="text-left"><?php echo $variation['special']; ?></td>
                                    <td class="text-left"><?php echo $variation['category_price']; ?></td>
                                </tr>
                                <?php }}} ?>
                            </tbody>
                        </table>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <?php echo $footer;?>
</div>

