<?php require(DIR_BASE.'front/ui/theme/kwikbasket/template/common/header.tpl'); ?>

<div class="container products-grid">
  <?php foreach($categories as $category) { ?>
  <?php $link_array = explode('/',$category['href']); $page_link = end($link_array); ?>
  <?php if(count($category['products']) > 0) { ?>
  <h3 class="category-title">
    <span>
      <?=$category['name']?>
    </span>
  </h3>
  <div class="row">
    <?php foreach($category['products'] as $product) { ?>
    <div class="col-md-2 products-grid-item" data-store="<?= $product['store_id'] ?>"
      data-id="<?= $product['product_store_id'] ?>">

      <img class="product-image" src="<?=$product['thumb']?>" alt="<?=$product['name']?>">
      <p class="product-name">
        <?= $product['name'] ?>
      </p>
      <p class="product-price">
        <?= $product['variations'][0]['special_price'] ?>
        <?php  echo '/ Per ' . $product['variations'][0]['unit']; ?>
      </p>

      <div id="<?= $product['product_id'] ?>-product-quantity" class="product-quantity-in-basket"
        style="display: <?= $product['qty_in_cart'] ? 'block' : 'none'; ?>">
        <?= $product['qty_in_cart'] ?>
      </div>
    </div>
    <?php } ?>
  </div>
  <div class="row mb-5">
    <div class="col-md-12 d-flex justify-content-center align-items-center">
      <a href="<?= $this->url->link('product/store', 'store_id='.ACTIVE_STORE_ID).'?cat='.$page_link?> "
        class="btn btn-sm btn-success px-4 py-2">View All
        <?=$category['name']?>
      </a>
    </div>
  </div>
  <?php } ?>
  <?php } ?>
</div>

<?php require(DIR_BASE.'front/ui/theme/kwikbasket/template/common/footer.tpl'); ?>