<div class="signupModal-popup">
        <div class="modal fade" id="reportissueModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close rightalign" data-dismiss="modal" aria-label="Close">
                        
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
<path d="M10 18C5.59 18 2 14.41 2 10C2 5.59 5.59 2 10 2C14.41 2 18 5.59 18 10C18 14.41 14.41 18 10 18ZM10 0C4.47 0 0 4.47 0 10C0 15.53 4.47 20 10 20C15.53 20 20 15.53 20 10C20 4.47 15.53 0 10 0ZM12.59 6L10 8.59L7.41 6L6 7.41L8.59 10L6 12.59L7.41 14L10 11.41L12.59 14L14 12.59L11.41 10L14 7.41L12.59 6Z" fill="#FF8888"/>
</svg>
                        </button>
                     <div class="store-find-block">
                        
                        <div class="store-find">
                            <div class="store-head">
                                <h1><?php echo $heading_title; ?></h1>
                                <h4></h4>
                            </div>
                            <div id="reportissue-message">
                            </div>
                            <div id="reportissue-success-message" style="color: green">
                            </div>
                            <!-- Text input-->
                            <!--<div class="store-form">-->
                                
                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="reportissue-form">
                            <fieldset>
                             
                                <div class="form-group" id="modal_bodyvalue">
                                   <!-- <label class="col-sm-12 control-label orderlabel super" style="background: #FFE4CB;text-align: center;padding-top: 0px" for="input-name">Order Id: <text id="modal_bodyvalue"></text></label>-->
                                    
                                </div>

                                
                               
                                <div class="form-group required">
                                    <label class="col-sm-4 control-label super" for="input-issuetype">Issue type:  </label>
                                    <div class="col-sm-8">
                                 <select name="selectissuetype"  id="issuetype" class="form-control newddl super" style='height: 45px; font-family:Arial, FontAwesome;'>
                                <option   value="Delivery"    selected="selected">&#xf0d1; &nbsp;Delivery</option>
                                <option disabled ></option>
                                <option   value="Items Delivered"      >&#xf07a; &nbsp;Items Delivered</option>
                                <option disabled  ></option> 
                                <option   value="Accounts"      >&#xf2bd; &nbsp;Accounts</option>
                                <option disabled  ></option> 
                                 <option   value="Payments"      >&#xf155; &nbsp;Payments</option>
                                <option disabled  ></option> 
                                 <option   value="Executives"      >&#xf2bb; &nbsp;Executives</option>

                            </select>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-4 control-label super" for="input-issuesummary">Issue Details</label>
                                    <div class="col-sm-8">
                                        <textarea name="issuesummary" rows="10" id="input-issuesummary" class="form-control super" value=""></textarea>
                                        <?php if ($error_issuesummary) { ?>
                                        <div class="text-danger"><?php echo $error_issuesummary; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                
                            </fieldset>
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                <div class="col-md-3 pull-right">
                                    <button id="reportissue" type="button"  style="background: #FFFFFF;border: 1px solid #00A307;box-sizing: border-box;box-shadow: 0px 4px 12px rgba(0, 163, 7, 0.09);border-radius: 4px;" name="next" class="btn-lg">
                                        <span class="reportissue-modal-text" style="color:green;"><?= $button_submit ?></span>
                                        <div class="reportissue-loader" style="display: none;"></div>
                                    </button>  </div>
                                <div class="col-md-3 pull-right">
                                      <button id="reportissueusclose" type="button"  data-dismiss="modal" style="background: #FFFFFF;border: 0px solid #3D3A3A;box-sizing: border-box;box-shadow: 0px 4px 12px rgba(0, 163, 7, 0.09);border-radius: 4px;" name="next" class="btn-lg">
                                        <span class="reportissue-modal-text" style="color:#3D3A3A;">Cancel</span>
                                         
                                    </button>

                                </div>
                            </div>
                        </form>

                            <!-- Text input</div> -->                           
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
 
    .rightalign{
width:8%;
    }
    .btn-lg{
        padding: 6px 8px;
        font-family: Montserrat;
font-style: normal;
font-weight: 600;
font-size: 16px;
line-height: 20px;
    }
    .super{
        font-family: Montserrat;
        font-style: normal;
font-weight: normal;
font-size: 16px;
line-height: 20px;
/* identical to box height */

letter-spacing: -0.02em;

color: #000000;
    }
    </style> 