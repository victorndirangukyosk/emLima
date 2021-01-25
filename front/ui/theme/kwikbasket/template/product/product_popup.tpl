<div class="modal fade" id="product-details-popup">
   <div class="modal-dialog modal-dialog-centered">
       <div class="modal-content">
           <div class="modal-header">
               <h4 class="modal-title">
                  {{ selectedProduct.info.name }}
               </h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
           </div>

           <div class="modal-body">
               <div class="container">
                   <div class="row">
                       <div class="col-md-12 d-flex justify-content-center align-items-center">
                           <img class="img-fluid product-thumbnail" :src="selectedProduct.info.thumb">
                       </div>
                   </div>
                   <h6 class="model-section-title">Available In</h6>
                   <div class="row modal-row">
                       <div class="col-md-12 variations-container">
                           <label id="variation-selector" v-for="variation in selectedProduct.info.variations" :key="variation.variation_id">
                               <input type="radio" name="variation" :value="variation" v-model="selectedProduct.popup.variation">
                               <span class="variation-pill">{{ variation.unit }}</span>
                           </label>
                       </div>
                   </div>
                   <h6 class="model-section-title">Product Notes (Optional)</h6>
                   <div class="row modal-row">
                       <div class="col-md-12 px-0">
                           <textarea class="form-control" id="product-notes"
                               placeholder="Tell us how you'd like this product e.g Big, Ripe, Peeled, etc."
                               v-model="selectedProduct.popup.productNotes"
                               rows="3"></textarea>
                       </div>
                   </div>
               </div>

               <div v-if="selectedProduct.popup.variation.price" class="price-container">
                   <p class="product-modal-price">{{ formatCurrency(selectedProduct.popup.variation.price) }}</p>
               </div>
           </div>
           
           <div class="modal-footer">
               <div class="container">
                   <div class="row">
                       <div class="col-md-12">
                           <div class="input-group">
                               <input id="product-quantity" class="form-control" placeholder="Quantity" v-model="selectedProduct.popup.quantity">
                               <input type="button" class="btn btn-cta-add" value="Add To Basket" :disabled="!selectedProduct.popup.isValidData" @click="addProductToCart">
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>