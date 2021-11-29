<?php echo $header; ?>

<div role="main" id="main" class="account-section container" style="min-height: 400px;">
    <div class="row mobile-title">
        <div class="col-md-12">
            <div class="content-wrapper with-padding static-page-heading">
                <h2><?= $text_heading3 ?> "<?= $zipcode ?>"</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="content-wrapper with-padding">

            <div class="ic-retailers-container">
                <ul class="retailer-cards-list unstyled">
                    <?php foreach($stores as $store){ ?>
                    <li>
                        <div class="retailer-card">
                            <a href="<?= $this->url->link('information/locations/start', 'store_id='.$store['store_id']) ?>" class="retailer-link">
                                <div class="card-header">
                                    <div style="background-image: url(image/<?= $store['logo'] ?>);color: #0a6249" class="logo"></div>
                                </div>
                            </a>
                            <a href="<?= $this->url->link('information/locations/start', 'store_id='.$store['store_id']) ?>" class="retailer-link">
                                <div class="card-body">
                                    <h2 class="name">
                                        <?= $store['name'] ?>
                                    </h2>
                                    <p>
                                      <?= $text_available ?>
                                    </p>
                                </div>
                            </a>
                            <a href="<?= $this->url->link('information/locations/start', 'store_id='.$store['store_id']) ?>" class="retailer-link">
                                <div class="card-footer">
                                    <?= $text_shop ?>
                                </div>
                            </a>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>

        </div>
    </div>
</div>

<?php echo $footer; ?> 
