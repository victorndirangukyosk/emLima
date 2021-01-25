<div class="product-popup-wrapper"></div>

<div id="mini-cart-panel" class="modal fixed-left fade" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-dialog-aside" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Basket</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div id="mini-cart-spinner">
               <img src="<?= $base ?>front/ui/theme/metaorganic/assets/images/spinner.gif" alt="Loading spiner">
            </div>
            <div class="mini-cart-content"></div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-cta">Checkout</button>
         </div>
      </div>
   </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/vuex/3.6.0/vuex.min.js"></script>
   <script src="<?= $base;?>front/ui/theme/metaorganic/assets/js/app.js"></script>
<script type="text/javascript">
   $(document).ready(function () {

      function openProductPopup(productStoreId, storeId) {
         return new Promise((resolve, reject) => {
            $.ajax({
               url: `index.php?path=product/product/view&product_store_id=${productStoreId}&store_id=${storeId}`,
               type: 'GET',
               success: function (data) {
                  $('.product-popup-wrapper').html(data);
                  $('#product-details-popup').modal('show');
                  resolve(data);
               },
               error: function (error) {
                  reject(error);
               }
            });
         });
      }

      $(document).delegate('.products-grid-item', 'click', function () {
         $('.products-grid-item').prop('disabled', true);
         openProductPopup($(this).attr('data-id'), $(this).attr('data-store'))
            .then((data) => {
               $('.products-grid-item').prop('disabled', false);
            })
      });

      $(document).delegate('.mini-cart-button', 'click', function () {
         $('#mini-cart-spinner').show();
         $.get('index.php?path=common/cart/newInfo', function(data) {
            $('.mini-cart-content').html(data);
            $('#mini-cart-spinner').hide();
         });
      });

      $('#header-product-search').autocomplete({
         delay: 500,
         minLength: 2,
         source: function (request, response) {
            $.ajax({
               url: `<?= $this->url->link('product/search/product_search') ?>&filter_name=${encodeURIComponent(request.term)}`,
               dataType: 'json',
               success: function (data) {
                  response($.map(data, function (item) {

                     if (item['product_id'] == 'getall') {
                        return {
                           label: item['name'],
                           name_label: item['name'],
                           value: item['product_id'],
                           href: item['href_cat'],
                           img: item['image'],
                           special_price: item['special_price'],
                           product_store_id: item['product_store_id'],
                           store_id: item['store_id'],
                           quantityadded: item['quantityadded']
                        }
                     } else {
                        return {
                           label: item['name'],
                           name_label: item['name'],
                           value: item['product_id'],
                           href: item['href_cat'],
                           img: item['image'],
                           special_price: item['special_price'],
                           unit: item['unit'],
                           product_store_id: item['product_store_id'],
                           store_id: item['store_id'],
                           quantityadded: item['quantityadded']
                        }
                     }
                  }));
               }
            });
         },
         create: function () {
            $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
               return $(`
                  <li>
                    <div class="container">
                      <div class="row">
                        <div class="col-md-12">
                          <img width="70" src="${item.img}">
                          <span class="ml-4">${item.label}</span>
                        </div>
                      </div>
                    </div>
                  </li>
                `).appendTo(ul);
            };
         },
         select: function (event, ui) {
            openProductPopup(ui.item.product_store_id, ui.item.store_id)
               .then(() => $('#header-product-search').val(''));
            return false;
         }
      });
   });
</script>

</body>

</html>