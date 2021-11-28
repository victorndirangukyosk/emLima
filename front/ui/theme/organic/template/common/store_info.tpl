<div class="shop-info">
    <div tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="false" class="citypearlmodel fade modal in" style="display: block;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="container-fluid">
                    <button class="close" onclick="$('.shop-info-container').html('')" data-dismiss="modal" type="button">
                        <span aria-hidden="true">x</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <div class="row">
                        
                        <div class="col-md-2">
                            <div class="citypearlogobox">
                                <?php if($thumb){ ?>
                                    <img src="<?= $thumb ?>" />
                                <?php }else{ ?>
                                    <img src="image/data/no_store.png" />
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="citypearlsadd">
                                <h4><?= $store['name'] ?></h4>
                                <p class="modeladdress">
                                    <span>
                                        <i class="fa fa-map-marker"></i>
                                    </span>
                                    <?= $store['address'] ?>
                                </p>
                                <a class="btn-green" id="change-store" href="<?= $this->url->link('common/home/show_home') ?>">
                                    <?= $button_change_store ?>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="deliversAdd">
                                <p>
                                    <span><?= $text_deliver ?></span>
                                </p>
                                <p><?= $zipcodes ?></p>
                            </div>
                        </div>
                        <div class="col-md-2">
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>