<div class="feedbackModal_popup" id="testfeedbackID"  style="width: 350px;">
    <div class="modal fade feedback-details" id="feedbackpopupmodal" id="feedback-cart-side" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="margin-top: 10rem;">
            <div class="modal-content  col-md-8 col-md-push-2 pl0 pr0">
                <div class="modal-body" id="feedback-body">
                    <button type="button" class="close close-model" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="row">
                        <div class="col-md-5" >
                            <div class="product-slider xyz" >
                                <div class="easyzoom easyzoom--overlay" >
                                Would you like to share some feedback ?                                   
                                </div>                               
                            </div>
                        </div>

                     </div>

                 </div>

                    
                    
             </div>
             
         </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).delegate('.close-model', 'click', function(){
       
            $('#feedbackpopupmodal').modal('hide');
            $('.modal-backdrop').remove();
    });

    $(document).delegate('.box-menu a', 'click',function(e){

        e.preventDefault(); 
        $.get('index.php?path=product/store/getVariation&product_id='+$product_id+'&variation_id='+$variation_id, function(data){
             
             
        });

        return false;
    });

      
</script>
