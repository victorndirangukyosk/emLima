<div class="signupModal-popup">
        <div class="modal fade" id="signupModal-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <div class="store-find-block">
                        
                        <div class="store-find">
                            <div class="store-head">
                                <h1><?= $text_welcome_message?></h1>
                                <h4></h4>
                            </div>
                            <div id="signup-message">
                            </div>
                            <!-- Text input-->
                            <div class="store-form">
                                <?php echo $account_register ?>
                            </div>
                            <p>
                                <label for="agree" class="">                                                
                                    <input class="checkbox-inline" type="checkbox" name="agree_checkbox" style="margin-top: 0px;"/>            

                                    <?= $text_enter_you_agree ?>                                      
                                            <a target="_blank" alt="<?= $text_terms_of_service ?>" href="<?= $account_terms_link ?>">
                                        <strong>  <?= $text_terms_of_service ?></strong> </a>

                                        &amp; 
                                        <a target="_blank" alt="<?= $text_privacy_policy?>" href="<?= $privacy_link ?>">
                                        <strong><?= $text_privacy_policy?></strong>
                                        </a>

                                    <div class="text-danger" id="error_agree" style="display: none"><?php echo $error_agree_terms; ?></div>
                                </label>                                    
                            </p>
                            <p><?= $text_have_account?> <a href="#" data-toggle="modal" data-dismiss="modal" data-target="#phoneModal" ><?= $text_log_in ?></a></p>
                        </div>
                          <div class="store-footer text-center">
                            <!-- <p><?= $text_enter_you_agree?> 

                            <a target="_blank" alt="<?= $text_terms_of_service ?>" href="<?= $account_terms_link ?>">
                            <strong><?= $text_terms_of_service ?></strong> </a>

                            &amp; 
                            <a target="_blank" alt="<?= $text_privacy_policy?>" href="<?= $privacy_link ?>">
                            <strong><?= $text_privacy_policy?></strong>
                            </a>

                             </p> -->
                            <!--<p>
                                <label for="agree" class="">                                                
                                    <input class="checkbox-inline" type="checkbox" name="agree_checkbox" style="margin-top: 0px;"/>            

                                    <?= $text_enter_you_agree ?>                                      
                                            <a target="_blank" alt="<?= $text_terms_of_service ?>" href="<?= $account_terms_link ?>">
                                        <strong>  <?= $text_terms_of_service ?></strong> </a>

                                        &amp; 
                                        <a target="_blank" alt="<?= $text_privacy_policy?>" href="<?= $privacy_link ?>">
                                        <strong><?= $text_privacy_policy?></strong>
                                        </a>

                                    <div class="text-danger" id="error_agree" style="display: none"><?php echo $error_agree_terms; ?></div>
                                </label>                                    
                            </p>-->
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>