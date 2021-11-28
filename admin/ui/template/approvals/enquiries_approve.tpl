
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?= $heading_text1 ?></h4>
        </div>
        <div class="modal-body">    

            <div class="message_wrapper"></div>

            <div class="form-group required">
                <label for="input-name" class="col-sm-3 control-label"><?= $text_vendor_group ?></label>
                <div class="col-sm-7">
                    <select name="user_group_id" class="form-control">
                        <?php foreach($rows as $usergroup){ ?>
                            <option value="<?= $usergroup['user_group_id'] ?>"><?= $usergroup['name'] ?></option>
                        <?php } ?>
                    </select>
                    <input type="hidden" name="enquiry_id" value="<?= $enquiry_id ?>">        
                </div>
            </div>
          
             <div class="form-group required">
                <label for="input-name" class="col-sm-3 control-label"><?= $text_commision ?></label>
                <div class="col-sm-7">
                    <input type="text" name="commision" value="" class="form-control" />                    
                </div>
             </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn_approve btn btn-primary"><?= $button_submit ?></button>
        </div>
    
        <script>
            $('.btn_approve').click(function() {

                $(this).attr('disabled', 'disabled').html('Sending data...').css('opacity', '0.5');

                //$.post("/index.php?path=approvals/enquiries/approve&token=<?= $this->session->data['token'] ?>", {
                $.post("<?= $action ?>"+"&token=<?= $this->session->data['token'] ?>", {
                    commision: $('input[name="commision"]').val(),
                    enquiry_id: $('input[name="enquiry_id"]').val(),
                    user_group_id: $('select[name="user_group_id"]').val()
               },
                function(data) {
                    var data = JSON.parse(data);
                    if (data.status == 1) {
                        location = location;
                    }
                });
            });
        </script>    

        <style>
            .form-group {
              margin-bottom: 15px;
              display: block;
              height: 30px;
            }
        </style>    