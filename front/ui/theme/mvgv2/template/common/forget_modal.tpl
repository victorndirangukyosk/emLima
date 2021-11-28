<div class="phoneModal-popup">
        <div class="modal fade" id="forgetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="store-find-block">
                            <div class="mydivsss">
                                <div class="store-find">
                                    <div class="store-head">
                                        <h1><?= $text_find_account ?></h1>
                                        <h4></h4>
                                    </div>
                                    <div id="forget-message">
                                    </div>
                                    <div id="forget-success-message" style="color: green">
                                    </div>
                                    <!-- Text input-->
                                    <div class="store-form">
                                        <form id="forget-form" action="<?= $forget_link?>" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="col-md-12 control-label sr-only" for="email"><?= $text_enter_email_address ?></label>
                                                    <div class="col-md-12">
                                                        <input id="email" name="email" type="text" placeholder="<?= $text_enter_email_address ?>" class="form-control input-md" required="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <button id="forget-button" type="button" name="next" class="btn btn-default btn-block btn-lg">
                                                            <span class="forget-modal-text"><?= $text_forget ?></span>
                                                            <div class="forget-loader" style="display: none;"></div>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="store-footer text-center">
                                <p><?= $text_enter_you_agree?> <strong><?= $text_terms_of_service ?></strong> &amp; <strong><?= $text_privacy_policy?></strong></p>
                            </div>
                            <!-- next div code -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>