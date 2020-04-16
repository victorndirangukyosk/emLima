<?php if($addresses){ ?>
    <?php foreach($addresses as $address){ ?>
        <div class="col-md-6">
            <div class="address-block">
                <h3 class="address-locations">
                <?php if($address['address_type'] == 'Home') { ?>
                        <?= $text_home_address ?>

                    <?php } elseif($address['address_type'] == 'Office') { ?>
                            <?= $text_office ?>
                    <?php } else {?>
                            <?= $text_other ?>
                    <?php }?>
                </h3>
                

                <h4 class="address-name"><?= $address['name'] ?></h4>
                <p><?php echo $address['flat_number'].',' ?><br> <?php echo $address['building_name'] ?>
                    <br><?php echo $address['city']; ?>
                </p>

                

                <?php if($this->config->get('config_store_location') == 'zipcode') { ?>

                    <?php if($address['zipcode'] == $zipcode ) { ?>
                        <a  data-address-id="<?= $address['address_id'] ?>" id="open-address" class="btn btn-primary btn-block"><?= $text_deliver_here?></a>
                    <?php } else { ?>
                        <a href="#" class="btn btn-grey btn-block disabled" role="button"><?= $text_not_deliver_here ?></a>
                    <?php } ?>
                

                <?php } else { ?>

                    <?php if( $address['show_enabled'] ) { ?>
                        <a  data-address-id="<?= $address['address_id'] ?>" id="open-address" class="btn btn-primary btn-block"><?= $text_deliver_here ?></a>
                    <?php } else { ?>
                        <a href="#" class="btn btn-grey btn-block disabled" role="button"><?= $text_not_deliver_here ?></a>
                    <?php } ?>
                
                <?php } ?>


                
            </div>
        </div>
    <?php } ?>
<?php } ?>