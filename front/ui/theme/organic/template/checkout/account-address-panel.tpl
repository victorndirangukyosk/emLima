<?php if($addresses) { ?>
                                         
    <?php foreach($addresses as $address){ ?>
        <div class="col-md-6">
            <div class="address-block">
                <!-- <h3 class="address-locations"><?= $address['address_type'] ?></h3> -->
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
                <p><?php echo $address['flat_number'].', ' ?><br>
                    <?php echo $address['building_name'] ?>
                    <br><?php echo $address['city']; ?>
                    </p>
                    <a  href="#" onclick="editAddressModal(<?= $address['address_id'] ?>)" type="button" data-toggle="modal" data-target="#editAddressModal" class="btn btn-default"> <?php echo $button_edit; ?></a>
                    <a  href="<?php echo $address['delete']; ?>" id="delete-address" class="btn btn-primary" onclick="return confirm('Are you sure?')"><?php echo $button_delete; ?></a>
            </div>
        </div>
    <?php } ?>
<?php } ?>