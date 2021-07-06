<?php //echo '<pre>';print_r($_SESSION);exit;?>

<?php echo $header; ?>
<div class="container">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="row"> 
        <div class="col-md-8">
             

            <div class="tab-content">
             
                <?php if(isset($_SESSION['success_msg']) && !empty($_SESSION['success_msg'])){?>
        <div class="alerter" id="message_text">
        <div class="alert alert-info normalalert">
        <p class="notice-text" >Success: <?php echo $_SESSION['success_msg']?>.</p>
        </div>

        </div>
        <?php $_SESSION['success_msg'] ='';?>
        <?php }?>
                <div id="allcontacts"   >
                    <?php //echo'<pre>';print_r($_SESSION);exit;?>

                    <div class="row">
                    <br>
                    </div>
                    <div class="row">

                       
                    <div class="col-md-12">
                    <h2>Customer Contacts</h2>
<div class="pull-right">


<button type="submit" data-style="zoom-out" id="add_contact" class="btn btn-default"><span class="ladda-label" style="padding-right:10px;">Add Contact</span><span class="ladda-spinner"></span></>
</div>
</div>
</div>
<br>

<div class="row" style="overflow:auto;">
 <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th> Email</th>
                                <th>Phone No</th>                                 
                                <th>Send Invoice</th> 
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($customer_contacts)){ ?>
                            <?php foreach($customer_contacts as $contact){ ?>
                            <tr id="contact<?php echo $contact['contact_id']; ?>">
                                <td><?php echo $contact['firstname'];?></td>
                                <td><?php echo $contact['lastname'];?></td>
                                <td><?php echo $contact['email'];?></td>
                                <td><?php echo $contact['telephone'];?></td>                                
                                <?php if($contact['send'] == '1') { ?>
                                <td><input type="checkbox" checked id="send_invoice_required" name="send_invoice_required" data-contactid="<?php echo $contact['contact_id']; ?>" title="Send invoice" disabled></td>
                                <?php } else { ?>
                                <td><input type="checkbox"   id="send_invoice_required" name="send_invoice_required" data-contactid="<?php echo $contact['contact_id']; ?>" title="Don't send invoice" disabled ?></td>
                                <?php } ?>
                                <td> 
                                <a  class="btn btn-success contactedit"   data-contact-id="<?php echo $contact['contact_id']; ?>" data-toggle="tooltip" title="Edit contact"><i class="fa fa-edit"></i></a>
                                <a data-confirm="Delete contact!" class="btn btn-success contactdelete"   data-contact-id="<?php echo $contact['contact_id']; ?>" data-toggle="tooltip" title="Delete contact"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php }else{ ?>
                            <tr style="text-align:center">
                                <td colspan="5">No Contact found</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    </div></div>
                </div>
                <div id="addContact"  hidden>
                    <form  autocomplete="off" method="post" action="<?php echo $action?>" id="add-contact-form" enctype="multipart/form-data" class="form-horizontal">
                        <div class="secion-row">
                            <br />
                                        <input type="hidden" value="0"  name="contactid" id="contactid"  />

                            <fieldset>
                                <div class="form-group required has-feedback">
                                    <label for="name" class="col-sm-3 control-label">Contact Person First Name</label>
                                    <div class="col-sm-6">
                                        <input type="text"  value=""  size="30" placeholder="Contact Person First Name" name="firstname" maxlength="100" id="input-firstname" class="form-control input-lg" />
                                        <?php if($error_firstname) { ?>
                                        <div class="text-danger"><?php echo $error_firstname; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="input-lastname">Contact Person Last Name</label>
                                    <div class="col-sm-6 col-xs-12">
                                        <input type="text" name="lastname" value="" placeholder="Contact Person Last Name" id="input-lastname" class="form-control input-lg" />
                                        <?php if($error_lastname) { ?>
                                        <div class="text-danger"><?php echo $error_lastname; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-emailnew">Contact Person Email</label>
                                    <div class="col-sm-6 col-xs-12">
                                        <input type="email" name="input-emailnew"  value="" placeholder="Contact Person Email"  id="input-emailnew"  class="form-control input-lg" />
                                        <?php if($error_email) { ?>
                                        <div class="text-danger"><?php echo $error_email; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

 

                                

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="input-telephone"><?php echo $entry_phone; ?></label>

                                    <div class="col-sm-6 col-xs-12 input-group" style="padding-right: 15px;padding-left: 15px;">

                                        <span class="input-group-btn">

                                            
                                            <p  class="phonesetbut" >

                                                <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                                +<?= $this->config->get('config_telephone_code') ?>
                                                </font></font></font>
                                                </font>
                                            </p>

                                        </span>

                                        <input type="tel" name="telephone"  value=""  id="input-telephone" class="form-control input-lg" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 & amp;
                                                & amp;
                                                event.charCode <= 57" minlength="9" maxlength="9" />

                                        <?php if ($error_telephone) { ?>
                                        <div class="text-danger"><?php echo $error_telephone; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
     
                               <div class="form-group required">
                                  <label class="col-sm-3 control-label" for="input-contactsend">Send Invoice</label>
                                  <div class="col-sm-6 col-xs-12">
                                        <input type="checkbox" checked   id="customer_contact_send" name="customer_contact_send">
                                             
                                        </input>
                                    </div> 
                               </div>
                               
                                  

                                <?php if ($site_key) { ?>
                                <div class="form-group  ">
                                    <label class="col-sm-3 control-label" for="input-date-added"></label>
                                    <div class="col-sm-6 col-xs-12 pl0 pr0">

                                        <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>" style="padding-left:16px"></div>
                                        <?php if ($error_captcha) { ?>
                                        <div class="text-danger"><?php echo $error_captcha; ?></div>
                                        <?php } ?>
                                    </div>

                                </div>
                                <?php } ?>

                            </fieldset>

                        </div>
                        <div class="col-sm-2 col-sm-pull-2 secion-row text-center" style="margin-bottom: 20px; float: right; margin-right: 63px">
                            <button type="submit" data-style="zoom-out" id="save-button" onclick="return validateAndSubmitForm()" class="btn btn-default"><span class="ladda-label"><?= $button_save ?></span><span class="ladda-spinner"></span></button>
                        </div>
                        <div class="col-sm-2 col-sm-pull-2 secion-row text-center" style="margin-bottom: 20px; float: right; ">
                            <button type="cancel" data-style="zoom-out" id="cancel-button"  style="background: transparent;color: #b1acac !important;" onclick="return CancelForm()" class="btn btn-default"><span class="ladda-label">Cancel</span><span class="ladda-spinner"></span></button>
                        </div>
                    </form>
                </div>
                 
            </div>

        </div>
    </div>



</div>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
<?php echo $footer; ?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?= $base?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?= $base?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>

<script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
<script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/header-sticky.js"></script>
</body>

<?php if ($kondutoStatus) { ?>
<script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>
<script type="text/javascript">
                                var __kdt = __kdt || [];
                                var public_key = '<?php echo $konduto_public_key ?>';
                                console.log("public_key");
                                console.log(public_key);
                                __kdt.push({"public_key": public_key}); // The public key identifies your store
                                __kdt.push({"post_on_load": false});
                                (function () {
                                    var kdt = document.createElement('script');
                                    kdt.id = 'kdtjs';
                                    kdt.type = 'text/javascript';
                                    kdt.async = true;
                                    kdt.src = 'https://i.k-analytix.com/k.js';
                                    var s = document.getElementsByTagName('body')[0];
                                    console.log(s);
                                    s.parentNode.insertBefore(kdt, s);
                                })();

                                var visitorID;
                                (function () {
                                    var period = 300;
                                    var limit = 20 * 1e3;
                                    var nTry = 0;
                                    var intervalID = setInterval(function () {
                                        var clear = limit / period <= ++nTry;
                                        console.log("visitorID trssy");
                                        if (typeof (Konduto.getVisitorID) !== "undefined") {
                                            visitorID = window.Konduto.getVisitorID();
                                            clear = true;
                                        }
                                        console.log("visitorID clear");
                                        if (clear) {
                                            clearInterval(intervalID);
                                        }
                                    }, period);
                                })(visitorID);


                                var page_category = 'my-account-page';
                                (function () {
                                    var period = 300;
                                    var limit = 20 * 1e3;
                                    var nTry = 0;
                                    var intervalID = setInterval(function () {
                                        var clear = limit / period <= ++nTry;
                                        if (typeof (Konduto.sendEvent) !== "undefined") {
                                            Konduto.sendEvent(' page ', page_category); //Programmatic trigger event
                                            clear = true;
                                        }
                                        if (clear) {
                                            clearInterval(intervalID);
                                        }
                                    },
                                            period);
                                })(page_category);


</script>

<?php } ?>
 
 
<!--  jQuery -->

<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />



<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>

<script src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.maskedinput.min.js" type="text/javascript"></script>

<script type="text/javascript">
  
      
    function validateAndSubmitForm() {
        var return_var = true;
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

        $('.text-danger').remove();
        if ($("input[name='firstname']").val() == "") {
            return_var = false;
            $('<div class="text-danger">First Name must be between 1 and 32 characters!</div>').insertAfter($("input[name='firstname']"));
        }
        if ($("#input-emailnew").val() == "") {
            return_var = false;
            $('<div class="text-danger">Email is mandatory</div>').insertAfter($("input[name='input-emailnew']"));
        } else if (expr.test($("#input-emailnew").val()) == false) {
            return_var = false;
            $('<div class="text-danger">Email address is not valid</div>').insertAfter($("input[name='email']"));
        }

        /*if ($("input[name='telephone']").val() == "") {
            return_var = false;
            $('<div class="text-danger">Telephone is mandatory</div>').insertAfter($("input[name='telephone']"));
        } else if ($("input[name='telephone']").val().length < 9) {
            return_var = false;
            $('<div class="text-danger">Telephone is not valid</div>').insertAfter($("input[name='telephone']"));
        }*/

    

         if(grecaptcha.getResponse() == ""){
         return_var = false;
         $('<div class="text-danger">Please validate captcha!</div>' ).insertAfter( $(".g-recaptcha"));
         }       
        return return_var;
         
        
    }


      function CancelForm() {
          
         $("#allcontacts").show();
   $("#addContact").hide();
             
        return false;
         
        
    }
</script>

<style>
    .nav-tabs>li {
        width: 33.3%;
    }

    .option_pay {
        margin-top:-3px !important;
    }
    .table-bordered>tbody>tr>td {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
</style>
<script>
$( "#add_contact" ).click(function() {
    
   $("#message_text").hide();

   $("#allcontacts").hide();
   $("#addContact").show();
});

  
    $(document).delegate('#email', 'blur', function () {
        console.log($(this).val());
        $.ajax({
            url: 'index.php?path=account/customer_contacts/EmailUnique',
            type: 'post',
            data: {email: $(this).val()},
            dataType: 'json',
            success: function (json) {
                if (json.success == false) {
                    console.log(json.success);
                    $("#save-button").prop('disabled', true);
                    $('<div class="text-danger">Email address should be unique</div>').insertAfter($("input[name='email']"));
                }

                if (json.success == true) {
                    console.log(json.success);
                    $("#save-button").prop('disabled', false);
                    $('.text-danger').remove();
                }
            }
        });
    });

    $(document).delegate('.contactdelete', 'click', function () {
        var choice = confirm($(this).attr('data-confirm'));
        if (choice) {
            
            var contact_id = $(this).attr('data-contact-id');

            $.ajax({
                url: 'index.php?path=account/customer_contacts/DeleteCustomerContacts',
                type: 'post',
                data: {contact_id: contact_id},
                dataType: 'json',
                success: function (json) {
                    console.log(json);
                    $('#contact' + contact_id).remove();
                      $("#message_text").hide();
                }
            });
        }
    });

    

      $(document).delegate('.contactedit', 'click', function () {
          
            
            var contact_id = $(this).attr('data-contact-id');

            $.ajax({
                url: 'index.php?path=account/customer_contacts/getCustomerContact',
                type: 'get',
                data: {contact_id: contact_id},
                dataType: 'json',
                success: function (json) {
                    //console.log(json['data']['lastname']);
                    console.log(json['data']['telephone']);
                    console.log(json['data']);
                      $("#message_text").hide();
                        $('#input-firstname').val(json['data']['firstname']);
                        $('#input-lastname').val(json['data']['lastname']);
                        $('#input-emailnew').val(json['data']['email']);
                        $('#input-telephone').val(json['data']['telephone']);
                        $('#contactid').val(json['data']['contact_id']);
                        $checked=json['data']['send'];
                        //alert($('#contactid').val());
                        if($checked=="1")
                        $('#customer_contact_send').prop( "checked", true );
                        else
                        $('#customer_contact_send').prop( "checked", false );



                      $("#allcontacts").hide();
                     $("#addContact").show();

                }
            });
        
    });
   
     
</script>
</body>
</html>
