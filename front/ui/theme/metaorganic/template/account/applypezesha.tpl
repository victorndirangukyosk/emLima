<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php echo $header; ?>
<div class="col-md-9 nopl">
    <div class="dashboard-cash-content">

        <div class="row">
            <div class="col-md-12">
                <div class="cash-info" style="padding-bottom: 50px;padding-top: 50px;"><h1><?= $text_balance ?></h1>
                </div>
            </div>
        
        <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    DOB / Date Of Incorporation 
                </div>
                <div class="col-md-6" id="pay_with" >
                   <input type="text" name="dob" value="<?php echo $dob; ?>" placeholder="DOB / Date Of Incorporation" data-date-format="dd/mm/YYYY" id="input-date-added" class="form-control date" autocomplete="off" required/>
                </div>
        </div>
            
        <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    KRA PIN
                </div>
                <div class="col-md-6" id="pay_with" >
                    <input type="text" value="<?php echo $kra; ?>" size="30" placeholder="KRA PIN" value="<?php echo $kra ?>" name="kra" maxlength="100" id="kra" class="form-control input-lg" />
                </div>
        </div>
            
        <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    Gender
                </div>
                <div class="col-md-6" id="pay_with" >
                <label class="control control--radio" style="display: initial !important;"> 
                    <?php if($gender == 'male') {?> 
                        <input type="radio" name="gender" data-id="8" value="male" checked="checked"> Male 
                    <?php } else {?>
                    <input type="radio" name="gender" data-id="8" value="male"> Male
                    <?php } ?>
                    <div class="control__indicator"></div>
                </label>

                <label class="control control--radio" style="display: initial !important;">
                    <?php if($gender == 'female') {?> 
                        <input type="radio" name="gender" data-id="9" value="female" checked="checked"> Female
                    <?php } else {?>
                    <input type="radio" name="gender" data-id="9" value="female"> Female
                    <?php } ?>                   
                    <div class="control__indicator"></div>
                </label>

                <label class="control control--radio" style="display: initial !important;">
                    <?php if($gender == 'other') {?> 
                        <input type="radio" name="gender" data-id="8" value="other" checked="checked"> Other
                    <?php } else {?>
                    <input type="radio" name="gender" data-id="8" value="other"> Other
                    <?php } ?>
                    <div class="control__indicator"></div>
                </label>
                </div>
        </div>    
        
        <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    National ID
                </div>
                <div class="col-md-6" id="pay_with" >
                    <input type="text" name="national_id" id="national_id" placeholder="National ID" value="<?php echo $national_id; ?>" class="form-control input-lg" />
                </div>
        </div>
       
       <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    Credit Period
                </div>
                <div class="col-md-6" id="pay_with" >
                    <select class="form-control input-lg" id="credit_period" name="credit_period"><option value="">Select Credit Period</option><option value="30+7 days - 1.5%">30+7 days - 1.5%</option><option value="30+15 days - 2%">30+15 days - 2%</option></select>
                </div>
        </div>                 
                    
        <form method="POST" enctype="multipart/form-data" id="copy_of_certificate_of_incorporation_form">
            <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    Copy Of Certificate Of Incorporation 
                </div>
                <div class="col-md-4" id="pay_with" >
                    <input id="copy_of_certificate_of_incorporation" name="copy_of_certificate_of_incorporation" class="form-control" type="file" accept=".png,.jpg, .jpeg" required>
                </div>
                <div class="col-md-2" id="pay_with">
                    <button type="submit" id="copy_of_certificate_of_incorporation_button" name="copy_of_certificate_of_incorporation_button" class="btn btn-primary">UPLOAD</button>
                </div>
            </div>
        </form>    

        <form method="POST" enctype="multipart/form-data" id="copy_of_bussiness_operating_permit_form">
            <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    Copy Of Bussiness Operating Permit
                </div>
                <div class="col-md-4" id="pay_with" >
                     <input id="copy_of_bussiness_operating_permit" name="copy_of_bussiness_operating_permit" class="form-control" type="file" accept=".png,.jpg, .jpeg" required>
                </div>                                  
                <div class="col-md-2" id="pay_with">
                    <button type="submit" id="copy_of_bussiness_operating_permit_button" name="copy_of_bussiness_operating_permit_button" class="btn btn-primary">UPLOAD</button>
                </div>
            </div>
        </form>    

        <form method="POST" enctype="multipart/form-data" id="copy_of_id_of_bussiness_owner_managing_director_form">    
            <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    Copy Of ID Of Bussiness Owner / Managing Director
                </div>
                <div class="col-md-4" id="pay_with" >
                     <input id="copy_of_id_of_bussiness_owner_managing_director" name="copy_of_id_of_bussiness_owner_managing_director" class="form-control" type="file" accept=".png,.jpg, .jpeg" required>
                </div>
                <div class="col-md-2" id="pay_with">
                    <button type="submit" id="copy_of_id_of_bussiness_owner_managing_director_button" name="copy_of_id_of_bussiness_owner_managing_director_button" class="btn btn-primary">UPLOAD</button>
                </div>
            </div>
        </form>
        
        <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-12" id="pay_with" >
                    <a href="#" type="button" class="btn-link text_green" data-toggle="modal" data-target="#addressModal"><i class="fa fa-check-square-o"></i> Terms & Condtions</a>
                </div>
        </div>

        <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available; text-align: center;" id="loan_offers">
            <button type="submit" id="submit_info_to_pezesha" name="submit_info_to_pezesha" class="btn btn-primary">SUBMIT FOR CREDIT APPROVAL THROUGH PEZESHA</button>
        </div>
        <div class="col-md-12">   
        <div class="alert alert-danger" id="error_msg" style="margin-bottom: 7px;">
        </div>    
        <div class="alert alert-success" style="font-size: 14px;" id="success_msg" style="margin-bottom: 7px;"></div>
        </div>    
        </div>



    </div>
</div>
<?php echo $footer; ?>

<div class="addressModal">
    <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="row">
                        <div class="col-md-12">
                            <h2>PEZESHA TERMS AND CONDITIONS</h2>
                        </div>
                        <div id="address-message" class="col-md-12" style="color: red">
                        </div>
                        <div id="address-success-message" style="color: green">
                        </div>
                        <div class="addnews-address-form">

                            <div class="form-group" id="parent_terms_conditions" style="height:300px; overflow: auto;">
                                <div class="col-md-12" id="terms_conditions">
  <div>
    <p>
      <span>DEFINITIONS </span>
    </p>
    <div>
      <div>
        1.&nbsp;<span
          >In this Agreement the following words and expressions (save where the
          context requires otherwise) bear the following meanings:
        </span>
      </div>
    </div>
    <div>
      <div>
        1.1.&nbsp;<span>“</span><span>Account</span
        ><span
          >” means an account held by you with Pezesha and which is opened and
          operated in accordance with the terms and conditions herein
          contained;</span
        >
      </div>
      <div>
        1.2.&nbsp;<span> “Applicable Interest Rate” </span
        ><span
          >means the rate of interest determined by Pezesha with respect to a
          Credit Facility application;</span
        >
      </div>
      <div>
        1.3.&nbsp;<span>"Credit Contract" </span
        ><span
          >means the agreement, incorporating the Credit Terms, automatically
          entered into and agreed between a Debtor and Pezesha
        </span>
      </div>
      <div>
        1.4.&nbsp;<span>“</span><span>Credit Reference Bureau</span
        ><span
          >” means a Credit Reference Bureau duly licensed under the laws of the
          Republic of Kenya, as amended from time to time, to </span
        ><span class="text_2">inter alia</span
        ><span
          >, collect and facilitate the sharing of customer credit information;
        </span>
      </div>
      <div>
        1.5.&nbsp;<span>“Lender”</span
        ><span> means Pezesha Africa Limited or Pezesha</span>
      </div>
      <div>
        1.6.&nbsp;<span>“Debtor”</span><span> means </span
        ><span>retailer of Kwik Basket Kenya LTD</span
        ><span> or customer of </span><span>Kwik Basket Kenya LTD</span
        ><span> or </span><span>customer</span><span> registered with </span
        ><span>Kwik Basket Kenya LTD</span
        ><span> and looking to borrow credit from the </span
        ><span>Kwik Basket Kenya LTD</span><span> Platform </span
        ><span>where Pezesha is a lender.</span>
      </div>
      <div>
        1.7.&nbsp;<span>“Equipment” </span
        ><span>includes any device you use to access </span
        ><span>credit from Pezesha</span><span>; </span>
      </div>
      <div>
        1.8.&nbsp;<span>“Facility Fee” – </span
        ><span
          >means the charge due to Pezesha from the Debtor in consideration for
          the Lender advancing the credit facility to the Debtor;</span
        >
      </div>
      <div>
        1.9.&nbsp;<span>“</span><span>IPRS</span
        ><span
          >” means the Integrated Population Registration System set up and
          maintained by the Government of Kenya under the Ministry of State for
          Immigration and Registration of Persons;
        </span>
      </div>
      <div>
        1.10.&nbsp;<span> “Pezesha”</span
        ><span>
          means Pezesha Africa Limited, a limited liability company incorporated
          under the Companies Act 2015 in the Republic of Kenya</span
        >
      </div>
      <div>
        1.11.&nbsp;<span>Kwik Basket Kenya LTD</span><span>, </span
        ><span
          >a limited liability Company incorporated under the Companies Act 2015
          Laws of Kenya within the Republic of Kenya having its registered
          office at P.O. Box 57666-00200, Heritan House Woodlands Road, Off.
          Argwings-kodhek Road, opposite DOD Headquarters, Nairobi, Kenya.</span
        >
      </div>
      <div>
        1.12.&nbsp;<span>“Repayment”</span
        ><span>
          means each instalment due and payable by a Debtor under a Credit
          Contract, which shall </span
        ><span>comprise of principal</span
        ><span>
          and any fees to Pezesha as well as any amount otherwise due and
          payable by the Debtor under the Credit Contract;</span
        >
      </div>
      <div>
        1.13.&nbsp;<span>“</span><span>Request</span
        ><span
          >” means a request or instruction received by Pezesha from you or
          purportedly from you through the System and upon which Pezesha is
          authorized to act;
        </span>
      </div>
      <div>
        1.14.&nbsp;<span>“</span><span>Services</span
        ><span
          >” shall include any form of Credit Facility facilities or products
          that Pezesha may offer you, as a Debtor, pursuant to this Agreement
          and as you may from time to time subscribe to and “</span
        ><span>Service</span><span>” shall be construed accordingly; </span>
      </div>
      <div>
        1.15.&nbsp;<span>“</span><span>System</span><span>” means the </span
        ><span
          >Kwik Basket Kenya LTD Platform which is a digital platform that
          allows the retailers to obtain an end-to-end solution to manage their
          field sales activities and grow their distributor channels including
          obtaining credit from third party lenders including the Lender (the
          “Platform”).</span
        >
      </div>
      <div>
        1.16.&nbsp;<span>“User” </span
        ><span>refers to the person who accesses and uses the </span
        ><span>platform;</span>
      </div>
      <div>
        1.17.&nbsp;<span>“</span><span>We</span><span>,” “</span><span>our</span
        ><span>,” and “</span><span>us</span
        ><span
          >,” means Pezesha and includes the successors and assigns of Pezesha;
        </span>
      </div>
      <div>
        1.18.&nbsp;<span>"</span><span>You</span><span>" or "</span
        ><span>your</span
        ><span
          >" means the User and includes the personal representatives of the
          User;</span
        >
      </div>
      <div>
        1.19.&nbsp;<span>The word “</span><span>User” </span
        ><span
          >shall include both the masculine and the feminine gender as well as
          juristic persons;</span
        >
      </div>
    </div>
    <p>&nbsp;</p>
    <ol>
      <li>
        <span>ACCEPTANCE </span>
      </li>
    </ol>
    <div>
      <div>
        1.1.&nbsp;<span>By selecting or conf</span><span>irming </span
        ><span
          >“accept” in the Pezesha Credit Application Process you will be deemed
          to have fully read and understood the contents of this Agreement and
          to have unconditionally accepted to be bound by its terms as amended
          from time to time.</span
        >
      </div>
      <div>
        1.2.&nbsp;<span
          >It remains your sole responsibility to seek independent legal advice
          on the legal consequences of entering this Agreement. You understand
          and accept that the cost of obtaining legal advice is to be </span
        ><span>borne</span><span> by you and not Pezesha.</span>
      </div>
      <div>
        1.3.&nbsp;<span
          >Once accepted this Agreement will bind you and your personal
          representatives on the one hand and Pezesha’s successors and assigns
          on the other hand.</span
        >
      </div>
      <div>
        1.4.&nbsp;<span
          >Any addition or alteration to this Agreement may be made from time to
          time by Pezesha and of which notice of 30 days shall be given to you
          by way of publication as provided herein shall be binding upon you as
          fully as if the same were contained in this Agreement. If you choose
          not to agree/accept to the addition or alteration of this agreement
          upon being notified within the 30 days, then Pezesha shall have the
          right to demand all outstanding payments owed to Pezesha to be fully
          repaid within 15 working days.
        </span>
      </div>
      <div>
        1.5.&nbsp;<span
          >It is your responsibility to review the terms of this Agreement
          regularly and to ensure that you understand any amendments made to
          them.</span
        >
      </div>
      <div>
        1.6.&nbsp;<span
          >You accept that if your credit facility becomes non-performing,
          Pezesha may deal with this Agreement in accordance with paragraph 14
          below.</span
        >
      </div>
      <div>
        1.7.&nbsp;<span
          >If you do not agree with any provision contained in this Agreement,
          please do not access or accept this Application Process in any
          way.</span
        >
      </div>
      <div>
        1.8.&nbsp;<span
          >A copy of this Agreement will be made available for you to download
          through </span
        ><span>a weblink</span><span>.</span>
      </div>
      <div>
        1.9.&nbsp;<span
          >If you have any question in relation to this Agreement, please
          contact Pezesha’s customer care desk at he</span
        ><span>lp</span><span>@pezesha.com</span>
      </div>
    </div>
    <p>&nbsp;</p>
    <ol>
      <li value="2">
        <span>PERSONAL INFORMATION</span>
      </li>
    </ol>
    <div>
      <div>
        2.1.&nbsp;<span>To use the Service, upon confirmat</span
        ><span
          >ion of interest to take up a Credit Facility with Pezesha. Pezesha
          will gain access to your personal information held by Kwik Basket
          Kenya LTD for the purpose of opening an account on your behalf on the
          Pezesha platform. Your obtained </span
        ><span>personal information will include but not limited to:</span>
      </div>
    </div>
    <div>
      <div>2.1.1.&nbsp;<span>Your full name</span></div>
      <div>2.1.2.&nbsp;<span>Passport/ID number</span></div>
      <div>2.1.3.&nbsp;<span>Your mobile telephone number</span></div>
      <div>2.1.4.&nbsp;<span>Store location</span></div>
      <div>
        2.1.5.&nbsp;<span
          >Data related to your transactions with the Kwik Basket Kenya
          LTD</span
        >
      </div>
      <div>
        2.1.6.&nbsp;<span
          >Any other information that Pezesha may require from time to
          time.</span
        >
      </div>
    </div>
    <div>
      <div>
        2.2.&nbsp;<span
          >You warrant that the information that you provide while opening your
          Account is true and accurate.</span
        >
      </div>
      <div>
        2.3.&nbsp;<span
          >You understand that providing false information will constitute a
          breach of this Agreement and may constitute a crime and that Pezesha
          reserves all right to take any and all measures available in law to
          obtain relief.</span
        >
      </div>
      <div>
        2.4.&nbsp;<span
          >You authorize Pezesha to verify the information you have provided on
          your identity through any source which Pezesha may in its sole
          discretion decide to use.</span
        >
      </div>
      <div>
        2.5.&nbsp;<span
          >You hereby authorize Pezesha to verify the information you have
          provided on your identity when opening your Account by obtaining and
          procuring your personal information contained in the IPRS and you
          further agree and consent to the disclosure and provision of such
          personal information by the Government of Kenya to Pezesha.
        </span>
      </div>
      <div>
        2.6.&nbsp;<span
          >You hereby authorize Pezesha through the Pezesha Application to
          access information contained on your Equipment, relating to but not
          limited to, your phone usage and your mobile money account transaction
          history for the purposes of Credit Facility appraisal and for the
          purposes set out in this Agreement in general.</span
        >
      </div>
      <div>
        2.7.&nbsp;<span
          >You hereby irrevocably authorize Pezesha to obtain your credit
          information from a Credit Reference Bureau.
        </span>
      </div>
      <div>
        2.8.&nbsp;<span
          >You understand and agree that Pezesha reserves the right to make
          periodic checks during the currency of this Agreement to establish
          your most current credit information.
        </span>
      </div>
      <div>
        2.9.&nbsp;<span
          >You understand and agree that Pezesha reserves the right to vary the
          terms of your Credit Facility including, but not limited to, the
          repayment period and interest payable based on, but not limited to,
          your most current credit information.</span
        >
      </div>
      <div>
        2.10.&nbsp;<span
          >In case of default you irrevocably authorize Pezesha to send your
          name, the transaction and the details of default to a Credit Reference
          Bureau for listing. You understand that this information may be used
          by banking institutions and other credit grantors in
          <span
            >assessing applications for credit by you, associated companies, and
            supplementary account holders and for occasional debt tracing and
            fraud prevention purposes. In case of default you also irrevocably
            authorise
          </span></span
        ><span
          >Pezesha to also send your name to an external debt collection agency
          to make efforts to collect any arrears in relation to your Credit
          Facility with Pezesha.</span
        >
      </div>
      <div>
        2.11.&nbsp;<span
          >In so far as the information in question is not protected by any laws
          on data protection in Kenya you hereby irrevocably authorize Pezesha
          to disclose, receive, record or utilize any or all your personal
          information or information or data relating to your Account and any
          details of your use of the Services:
        </span>
      </div>
    </div>
    <div>
      <div>
        2.11.1.&nbsp;<span
          >to and from any local or international law enforcement or competent
          regulatory or governmental agencies so as to assist in the prevention,
          detection, investigation or prosecution of criminal activities or
          fraud;</span
        >
      </div>
      <div>
        2.11.2.&nbsp;<span
          >to and from the Pezesha’s service providers, dealers, agents or any
          other company that may be or become the Pezesha’s subsidiary or
          holding company for reasonable commercial purposes relating to the
          Services;
        </span>
      </div>
      <div>
        2.11.3.&nbsp;<span
          >for reasonable commercial purposes connected to your use of the
          Services, such as marketing and research related activities; and
        </span>
      </div>
      <div>
        2.11.4.&nbsp;<span
          >in business practices including but not limited to quality control,
          training and ensuring effective systems operation.
        </span>
      </div>
    </div>
    <p>&nbsp;</p>
    <ol>
      <li value="3">
        <span>CREDIT APPLICATION</span>
      </li>
    </ol>
    <div>
      <div>
        3.1.&nbsp;<span
          >An account is only opened on your behalf once you have stated clearly
          your intentions or acceptance of a credit facility offer from Pezesha.
          An account opening provides Pezesha permission to access your
          transactional data from Kwik Basket Kenya LTD and to forward funds to
          Kwik Basket Kenya LTD who will then top up your Kwik Basket Kenya LTD
          Merchant account on the Kwik Basket Kenya LTD Dashboard.</span
        >
      </div>
      <div>
        3.2.&nbsp;<span
          >Your credit limit, the applicable rate of interest and the
          application fees (“the Terms of the Credit Facility”) will be
          determined by Pezesha in its sole discretion. In making its
          determination Pezesha will make a credit assessment after considering
          information from various sources, including but not limited to,
          your</span
        ><span> business transaction history with Kwik Basket Kenya LTD,</span
        ><span>
          your mobile money account transaction history, your credit information
          from the Credit Reference Bureau, your history of use of the Services
          and prevailing market conditions.</span
        >
      </div>
      <div>
        3.3.&nbsp;<span
          >Pezesha will after assessing you as a Debtor, propose Credit Facility
          offers and </span
        ><span
          >notify you of the Terms of the Credit Facility and will give you an
          option asking you to confirm whether or not you have read, understood
          and accepted to be bound by the Terms of the Credit Facility.</span
        >
      </div>
      <div>
        3.4.&nbsp;<span>If you</span><span> accept the </span
        ><span
          >Credit Facility from Pezesha, you will get confirmation of acceptance
          by SMS or mobile application.</span
        >
      </div>
      <div>
        3.5.&nbsp;<span
          >If you accept the Terms of the Credit Facility you will be bound to
          repay the Credit Facility that has been advanced to you strictly on
          those terms and the terms of this Agreement.</span
        >
      </div>
      <div>
        3.6.&nbsp;<span
          >Once you have accepted the Terms of the Credit Facility, Pezesha will
          process your Credit Facility application and instruct </span
        ><span>Kwik Basket Kenya LTD to nominally </span
        ><span>credit your </span><span>Kwik Basket Kenya LTD </span
        ><span>Account </span
        ><span
          >with the equivalent amount of the Credit Facility taken. The Credit
          Facility will be initiated upon you signing the terms and conditions
          and expressing direct interest for the Credit Facility through the
          available official channels.</span
        >
      </div>
      <div>
        3.7.&nbsp;<span
          >Notwithstanding anything that is contained in this paragraph, Pezesha
          retains the right to approve or reject your Credit Facility
          application without assigning any reason.</span
        >
      </div>
    </div>
    <ol>
      <li value="4">
        <span>CREDIT FACILITY REPAYMENT</span>
      </li>
    </ol>
    <div>
      <div>
        4.1.&nbsp;<span
          >You understand and accept that the Applicable Interest Rate is a </span
        ><span>daily </span><span>rate of interest charged and applied</span
        ><span> on a reducing balance</span><span> basis for the dura</span
        ><span>tion of the Credit Facility.</span>
      </div>
      <div>
        4.2.&nbsp;<span
          >You can repay your Credit Facility either by instalments or by making
          a single lump sum payment within </span
        ><span
          >the agreed duration of the Credit Facility from the date the Credit
          Facility proceeds are credited to your Kwik Basket Kenya LTD
          Account</span
        ><span>.</span>
      </div>
      <div>
        4.3.&nbsp;<span>You will make your repayments solely </span
        ><span>through Pezesha's</span><span> M-Pesa </span
        ><span>Till Number- 725432- PEZESHA AFRICA</span>
      </div>
    </div>
    <p>&nbsp;</p>
    <div>
      <div>
        4.4.&nbsp;<span
          >For the avoidance of doubt, Credit Facility repayments will be deemed
          to have been made upon their being credited into the Pezesha </span
        ><span>till</span><span> or as Agreed with </span
        ><span>Kwik Basket Kenya LTD</span><span>.</span>
      </div>
      <div>
        4.5.&nbsp;<span
          >You will repay the Credit Facility without any set off or </span
        ><span>counterclaim</span
        ><span>
          and save in so far as required by the law to the contrary, free and
          clear of and without any deduction or withholding whatsoever. If you
          are at any time required to make any deduction or withholding from any
          payment to Pezesha you shall immediately pay to Pezesha such
          additional amounts as will result in Pezesha receiving the full amount
          it would have received had no such deduction or withholding been
          required.</span
        >
      </div>
    </div>
    <p class="block_8"><span>6. </span><span>DEFAULT</span></p>
    <ol>
      <li value="5">
        <span>1 An event of default occurs when you:-</span>
      </li>
    </ol>
    <div>
      <div>
        5.0.1.&nbsp;<span
          >Fail to repay the Credit Facility according to the provisions of
          paragraph 4 and 5 above.
        </span>
      </div>
      <div>5.0.2.&nbsp;<span>Become insolvent.</span></div>
    </div>
    <div>
      <div>
        5.1.&nbsp;<span>Upon your default Pezesha will be entitled to:-</span>
      </div>
    </div>
    <div>
      <div>
        5.1.1.&nbsp;<span
          >Demand immediate repayment of all outstanding amounts due to Pezesha
          within a period of Pezesha’s choosing;</span
        >
      </div>
      <div>
        5.1.2.&nbsp;<span
          >Deny you further Credit Facilities and/or access to Pezesha’s
          services;</span
        >
      </div>
      <div>
        5.1.3.&nbsp;<span
          >Send information regarding the Credit Facility and your default to a
          Credit Reference Bureau;</span
        >
      </div>
      <div>
        5.1.4.&nbsp;<span
          >To pursue any means of debt recovery available at law and any other
          measures. Pezesha may in its sole discretion decide to take;</span
        ><span>
          including seizing your inventory or other assets and blacklisting you
          to suppliers.</span
        >
      </div>
      <div>
        5.1.5.&nbsp;<span
          >You may incur penalties on late payments in line with the interest
          rate set out in this agreement.</span
        >
      </div>
    </div>
    <div>
      <div>
        5.2.&nbsp;<span>If you relocate your business or close shop;</span>
      </div>
    </div>
    <div>
      <div>
        5.2.1.&nbsp;<span>
          You will be required to commit to still paying the Credit Facility
          even if any of the events happen. The Pezesha collections team will
          follow up with you.
        </span>
      </div>
    </div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <ol>
      <li value="6">
        <span>IRREVOCABLE AUTHORITY TO PEZESHA </span>
      </li>
    </ol>
    <div>
      <div>
        6.1.&nbsp;<span
          >You hereby irrevocably authorize Pezesha to act on all Requests
          received by Pezesha from you through the System and to hold you liable
          in respect thereof, notwithstanding that any such requests are not
          authorized by you.</span
        >
      </div>
      <div>
        6.2.&nbsp;<span class="text_5"
          >Pezesha shall be entitled to accept and to act upon any request, even
          if that request is otherwise for any reason incomplete or ambiguous,
          if, in its absolute discretion, Pezesha determines that the incomplete
          or ambiguous information is immaterial to its action on the
          Request.</span
        ><span class="text_6"></span>
      </div>
      <div>
        6.3.&nbsp;<span
          >If you Request Pezesha to cancel any transaction or instruction after
          a Request has been received by Pezesha from you, Pezesha may at its
          absolute discretion cancel such transaction or instruction but shall
          have no obligation to do so.
        </span>
      </div>
      <div>
        6.4.&nbsp;<span
          >Pezesha shall be deemed to have acted properly and to have fully
          performed all the obligations owed to you notwithstanding that the
          Request may have been initiated, sent or otherwise communicated in
          error or fraudulently, and you shall be bound by any Requests on which
          Pezesha may act if Pezesha has in good faith acted in the belief that
          such instructions have been sent by you.</span
        >
      </div>
      <div>
        6.5.&nbsp;<span
          >You agree to and shall release from and indemnify Pezesha against all
          claims, losses, damages, costs and expenses howsoever arising in
          consequence of, or in any way related to Pezesha having acted in
          accordance with the whole or any part of any of your Requests (or
          failed to exercise) the discretion conferred upon it.&nbsp;</span
        >
      </div>
      <div>
        6.6.&nbsp;<span
          >You acknowledge that to the full extent permitted by law Pezesha
          shall not be liable for any unauthorized drawing, transfer,
          remittance, disclosure, any activity or any incident on your account
          by the fact of the knowledge and/or use or manipulation of your
          Account login credentials or any means whether or not occasioned by
          your negligence.</span
        >
      </div>
      <div>
        6.7.&nbsp;<span
          >You accept and understand that Pezesha is authorized to effect such
          orders in respect of your Account as may be required by any court
          order or competent authority or agency under the applicable
          laws.&nbsp;</span
        >
      </div>
      <div>
        6.8.&nbsp;<span
          >In the event of any conflict between any terms in this agreement and
          any Request received by Pezesha from you and the terms of this
          Agreement shall prevail.
        </span>
      </div>
    </div>
    <ol>
      <li value="7">
        <span>DEBTOR’S RESPONSIBILITIES</span>
      </li>
    </ol>
    <div>
      <div>
        7.1.&nbsp;<span
          >You shall follow all instructions, procedures and terms contained in
          this Agreement and any document provided by Pezesha or </span
        ><span>Kwik Basket Kenya LTD </span
        ><span>concerning the use of the System and the Services.</span>
      </div>
      <div>
        7.2.&nbsp;<span>You shall take all reasonable </span
        ><span
          >efforts to ensure that the correct and agreed upon Credit Facility
          amount has been disbursed to Kwik Basket Kenya LTD on your
          behalf.</span
        >
      </div>
      <div>
        7.3.&nbsp;<span
          >You shall take all reasonable efforts to ensure Credit Facility
          repayments are successful and paid to the correctly provided business
          account with Kwik Basket Kenya LTD. Where any doubt arises you shall
          immediately contact Kwik Basket Kenya LTD or Pezesha for
          clarification, and/or reversal of any adverse payments made to a wrong
          business account.</span
        >
      </div>
      <div>
        7.4.&nbsp;<span><span>You undertake</span></span
        ><span
          ><span>
            to make good your repayments within the stipulated time and within
            the contract terms.</span
          ></span
        >
      </div>
    </div>
    <p>&nbsp;</p>
    <ol>
      <li value="8">
        <span>INDEMNITY </span>
      </li>
    </ol>
    <div>
      <div>
        8.1.&nbsp;<span
          >In consideration of Pezesha complying with your instructions or
          Requests in relation to your Account, you undertake to indemnify
          Pezesha and hold it harmless against any loss, charge, damage,
          expense, fee or claim which Pezesha suffers or incurs or sustains
          thereby and you absolve Pezesha from all liability for loss or damage
          which you may sustain from Pezesha acting on your instructions or
          requests or in accordance with this Agreement.
        </span>
      </div>
      <div>
        8.2.&nbsp;<span>The indemnity in sub-paragraph </span><span>8</span
        ><span>1 shall also cover the following: </span>
      </div>
    </div>
    <div>
      <div>
        8.2.1.&nbsp;<span
          ><span
            >All demands, claims, actions, losses and damages of whatever nature
            which may be brought against
          </span></span
        ><span
          >Pezesha or which it may suffer or incur arising from its acting or
          not acting on any Request or arising from the malfunction or failure
          or unavailability of a<span
            >ny hardware, software, or equipment, the loss or destruction of any
            data, power failures, act of God, pandemic, corruption of storage
            media, natural phenomena, riots, acts of vandalism, sabotage,
            terrorism, any other event beyond
          </span></span
        ><span
          >Pezesha’s control, interr<span
            >uption or distortion of communication links or arising from
            reliance on any person or any incorrect, illegible, incomplete or
            inaccurate information or data contained in any Request received by
          </span></span
        ><span>Pezesha. </span>
      </div>
      <div>
        8.2.2.&nbsp;<span
          >Any loss or damage that may arise from your use, misuse, abuse or
          possession of any third party software, including without limitation,
          any operating system, browser software or any other software packages
          or programs.
        </span>
      </div>
      <div>
        8.2.3.&nbsp;<span
          >Any unauthorized access to your Account or any breach of security or
          any destruction or accessing of your data or any destruction or theft
          of or damage to any of your Equipment.
        </span>
      </div>
      <div>
        8.2.4.&nbsp;<span
          >Any loss or damage occasioned by the failure by you to adhere to this
          Agreement and/or by supplying of incorrect information or loss or
          damage occasioned by the failure or unavailability of third party
          facilities or systems or the inability of a third party to process a
          transaction or any loss which may be incurred by Pezesha as a
          consequence of any breach of this Agreement.
        </span>
      </div>
      <div>
        8.2.5.&nbsp;<span
          >Any damages and costs payable to Pezesha in respect of any claims
          against Pezesha for recompense for loss where the particular
          circumstance is within your control.</span
        >
      </div>
    </div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <ol>
      <li value="9">
        <span>INTELLECTUAL PROPERTY RIGHTS</span><span>. </span>
      </li>
    </ol>
    <div>
      <div>
        9.1.&nbsp;<span
          >All copyright, trademarks, patents and other intellectual property
          rights in any material or content (including without limitation
          software, data, source code, applications, information, text,
          photographs, music, sound, videos, graphics, logos, symbols, artwork
          and other material or moving images) contained in or accessible via
          the System (“Intellectual Property”) is either owned by us or has been
          licensed to us by the rights owner(s) for use as part of the Services.
          You will not infringe any such intellectual property rights. Having
          noted the foregoing you shall not be entitled in respect of any
          Intellectual Property to change, edit, modify, reformat or adapt it in
          any way, sell, reproduce, display, distribute, or otherwise use the
          Intellectual Property in any way for any non-private, public or
          commercial purpose without our written consent.
        </span>
      </div>
      <div>
        9.2.&nbsp;<span
          >If you violate any of the foregoing provisions, your permission to
          use the Intellectual Property automatically terminates and you must
          immediately destroy any copies you have of the Intellectual
          Property.</span
        >
      </div>
    </div>
    <ol>
      <li value="10">
        <span>ASSIGNMENT, NOVATION AND OTHER DEALINGS </span>
      </li>
    </ol>
    <div>
      <div>
        10.1.&nbsp;<span
          >Pezesha may assign or novate this Agreement or otherwise deal with
          the benefit of it or a right under it, or purport to do so, without
          your prior written consent.</span
        >
      </div>
      <div>
        10.2.&nbsp;<span
          >You may not assign or novate this Agreement in the manner described
          in sub-paragraph 11.1 or at all.</span
        >
      </div>
    </div>
    <ol>
      <li value="11">
        <span>NOTICES </span>
      </li>
    </ol>
    <div>
      <div>
        11.1.&nbsp;<span
          >Pezesha may send information concerning your Account through </span
        ><span>a phone call, </span><span>via <span>SMS to</span></span
        ><span>
          the mobile phone number or email address associated with your
          Account.</span
        >
      </div>
      <div>
        11.2.&nbsp;<span
          >You accept that you have no claim against the Pezesha for damages
          resulting from losses, delays, misunderstandings, mutilations,
          duplications or any other irregularities due to mis-transmission of
          <span>any communication</span></span
        ><span> pertaining to your Account.</span>
      </div>
    </div>
    <ol>
      <li value="12">
        <span>DISPUTE RESOLUTION</span>
      </li>
    </ol>
    <div>
      <div>
        12.1.&nbsp;<span
          >If any dispute arises between the Parties to this Agreement regarding
          any provision of this Agreement, or its application or termination,
          then we agree that we will attempt to resolve our dispute peaceably by
          means of joint co-operation or discussion between the parties directly
          involved in the dispute within five (5) days after notification of
          <span
            >the dispute or such extended time period as we may agree to.
          </span></span
        >
      </div>
      <div>
        12.2.&nbsp;<span
          >In the event that the Parties are unable to resolve the dispute, that
          dispute shall be finally settled through Arbitration conducted under
          the Laws of Arbitration of the Republic of Kenya by a single
          Arbitrator to be appointed by the parties jointly. The cost of the
          arbitration will be borne equally by the parties.</span
        >
      </div>
      <div>
        12.3.&nbsp;<span
          >If the parties shall not be able to settle on the single arbitrator
          after fourteen (14) days of the request to result to Arbitration, the
          aggrieved party may request the chairperson of the Chartered Institute
          of Arbitration Kenya chapter to appoint the single Arbitrator.</span
        >
      </div>
      <div>
        12.4.&nbsp;<span
          >The seat of the Arbitrator shall be in Nairobi and the arbitral
          proceedings shall be in English.
        </span>
      </div>
      <div>
        12.5.&nbsp;<span
          >This sub-paragraph shall constitute your irrevocable consent to the
          arbitration proceedings, and you shall not be entitled to withdraw
          your consent or to claim that you are not bound by this sub-paragraph.
        </span>
      </div>
    </div>
    <ol>
      <li value="13">
        <span>GOVERNING LAW AND JURISDICTION</span>
      </li>
    </ol>
    <div>
      <div>
        13.1.&nbsp;<span
          >You agree that this Agreement shall be governed by and construed in
          accordance with the laws of the Republic of Kenya.</span
        >
      </div>
      <div>
        13.2.&nbsp;<span
          >You agree to the exclusive jurisdiction of the courts of the Republic
          of Kenya in respect of disputes which may arise out of your use of
          Pezesha’s services and this Agreement.</span
        >
      </div>
    </div>
    <ol>
      <li value="14">
        <span>MISCELLANEOUS</span>
      </li>
    </ol>
    <div>
      <div>
        14.1.&nbsp;<span
          >The parties are to act with loyalty and good faith towards one
          another.</span
        >
      </div>
      <div>
        14.2.&nbsp;<span
          >The failure by Pezesha to enforce at any time or for any period any
          one or more of the terms or conditions of this Agreement shall not be
          a waiver of them or of the right at any time subsequently to enforce
          all terms and conditions of this Agreement.</span
        >
      </div>
      <div>
        14.3.&nbsp;<span
          >If any provision of this Agreement is declared by any judicial or
          other competent authority to be void, voidable, illegal or otherwise
          unenforceable or indications to that effect are received by either of
          the parties from any competent authority, the parties shall amend that
          provision in such reasonable manner as achieves the intention of the
          parties without illegality.</span
        >
      </div>
    </div>
    <ol>
      <li value="15">
        <span>TERMINATION</span>
      </li>
    </ol>
    <div>
      <div>
        15.1.&nbsp;<span
          >Pezesha may at any time, upon notice to you, terminate or vary its
          business relationship with you or </span
        ><span>Kwik Basket Kenya LTD </span><span>and close your Account.</span>
      </div>
      <div>
        15.2.&nbsp;<span
          >Without prejudice to Pezesha’s rights under sub-paragraph 1</span
        ><span>6</span
        ><span
          >1, Pezesha may at its sole discretion suspend or close your
          Account:</span
        >
      </div>
    </div>
    <div>
      <div>
        15.2.1.&nbsp;<span>if you use the </span
        ><span>provided Credit Facility</span
        ><span>
          for unauthorised purposes or where Pezesha detects any abuse/misuse,
          breach of content, fraud or attempted fraud relating to your use of
          the Services;</span
        >
      </div>
      <div>
        15.2.2.&nbsp;<span>if your use of the </span
        ><span>provided Credit Facility</span
        ><span>
          in the opinion of Pezesha is in contravention with the law of Kenya
          and or illegal and not limited to money laundering activities.
        </span>
      </div>
      <div>
        15.2.3.&nbsp;<span
          >if Pezesha is required or requested to comply with an order or
          instruction of or a recommendation from the government, court of
          competent jurisdiction, regulator or other competent authority;</span
        >
      </div>
      <div>
        15.2.4.&nbsp;<span
          >if Pezesha reasonably suspects or believes that you are in breach of
          the terms of this Agreement which you fail to remedy (if remediable)
          within the time given for you to respond to any notice sent to you
          requiring you to do so;</span
        >
      </div>
      <div>
        15.2.5.&nbsp;<span
          >where such a suspension or variation is necessary as a consequence of
          technical problems or for reasons of safety; to facilitate update or
          upgrade the contents or functionality of the Services from time to
          time; where your Account becomes inactive or dormant; or</span
        >
      </div>
      <div>
        15.2.6.&nbsp;<span
          >if Pezesha decides to suspend or cease the provision of the Services
          for commercial reasons or for any other reason as it may determine in
          its absolute discretion.</span
        >
      </div>
    </div>
    <div>
      <div>
        15.3.&nbsp;<span
          >Termination shall however not affect any accrued rights and
          liabilities of either party.</span
        >
      </div>
      <div>
        15.4.&nbsp;<span
          >If Pezesha receives notice of your demise, Pezesha will not be
          obliged to allow any operation or withdrawal from your Account by any
          person except upon production of administration letters from a
          competent authority or a confirmed grant of letters of administration
          or a confirmed grant of probate by your legal representatives duly
          appointed by a court of competent jurisdiction.</span
        >
      </div>
    </div>
    <p>&nbsp;</p>
    <p>
      <span
        >I, …………………………………….. accept the terms stated in this agreement and
        acknowledge and that I will use the Credit Facility for only the purpose
        of purchasing products from Kwik Basket Kenya LTD and I hereby consent
        by attaching my signature and personal details as stipulated
        below;</span
      >
    </p>
    <p>
      <span>Debtor Name …………………………………….. </span>
    </p>
    <p>
      <span>Debtor ID ……………………………………..</span>
    </p>
    <p>
      <span>Debtor Location ……………………………………..</span>
    </p>
    <p>
      <span>Debtor Signature ……………………………………..</span>
    </p>
    <p><span>Date ……………………………………………</span><span>…..</span></p>
    <p>&nbsp;</p>
  </div>

                                </div>
                            </div>

                            <!-- Button -->
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button id="singlebutton" name="singlebutton" data-terms="0" type="button" class="btn btn-primary" data-dismiss="modal">AGREE</button>
                                    <button id="cancelbutton" name="cancelbutton" type="button" class="btn btn-grey  cancelbut" data-dismiss="modal">DECLINE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
<script src="<?= $base ?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
<script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.sticky.min.js"></script>
<script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/header-sticky.js"></script>
<script type="text/javascript" src="<?= $base ?>front/ui/javascript/jquery/infinitescroll/jquery.infinitescroll.min.js" ></script>
<script type="text/javascript" src="<?= $base ?>front/ui/javascript/jquery/infinitescroll/manual-trigger.js" ></script>

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


    var page_category = 'pezesha-page';
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
<script type="text/javascript">
$('#success_msg').hide();
$('#error_msg').hide();    
$('#copy_of_certificate_of_incorporation_button').on('click', function(e) {
    e.preventDefault();
    $('#success_msg').hide();
    $('#error_msg').hide();
    if( document.getElementById("copy_of_certificate_of_incorporation").files.length == 0 ){
    $('#error_msg').html('Copy Of Certificate Of Incorporation Sholud Not Be Empty!');
    $('#error_msg').show();
    return false;
    }
    
    const fi = document.getElementById('copy_of_certificate_of_incorporation');
    if (fi.files.length > 0) {
            for (var i = 0; i <= fi.files.length - 1; i++) {
  
                const fsize = fi.files.item(i).size;
                const file = Math.round((fsize / 1024));
                // The size of the file.
                if (file > 2048) {
                    $('#error_msg').html('Copy Of Certificate Of Incorporation Sholud Be Less Than 2 MB!');
                    $('#error_msg').show();
                return false;
                }
            }
    }
    
    var fileInput = document.getElementById('copy_of_certificate_of_incorporation');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
    if (!allowedExtensions.exec(filePath)) {
    $('#error_msg').html('Copy Of Certificate Of Incorporation File Type Invalid!!');
    $('#error_msg').show();            
    return false;
    } 
    
    var file_data = $('#copy_of_certificate_of_incorporation').prop('files')[0];
    var form_data = new FormData();
    form_data.append("file", file_data);
    $.ajax({
            type: 'post',
            url: 'index.php?path=account/applypezesha/pezeshafiles',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
            $('#copy_of_certificate_of_incorporation_button').prop('disabled', true);   
            },
            complete: function() {
            setInterval(function(){
            $('#copy_of_certificate_of_incorporation_button').prop('disabled', false);
            $('#copy_of_certificate_of_incorporation_button').html('UPLOAD');
            }, 1000);
            },
            success: function (response) {
            console.log(response);
            
            if(response.status == 200) {
            $('#copy_of_certificate_of_incorporation_button').html('<i class="fa fa-check" aria-hidden="true"></i>');   
            }
            
            if(response.status == 500) {
            $('#copy_of_certificate_of_incorporation_button').html('<i class="fa fa-times" aria-hidden="true"></i>');
            }
            
            }

    });
});
$('#copy_of_bussiness_operating_permit_button').on('click', function(e) {
    e.preventDefault();
    $('#success_msg').hide();
    $('#error_msg').hide();
    if( document.getElementById("copy_of_bussiness_operating_permit").files.length == 0 ){
    $('#error_msg').html('Copy Of Bussiness Operating Permit Sholud Not Be Empty!');
    $('#error_msg').show();    
    return false;
    }
    
    const fi = document.getElementById('copy_of_bussiness_operating_permit');
    if (fi.files.length > 0) {
            for (var i = 0; i <= fi.files.length - 1; i++) {
  
                const fsize = fi.files.item(i).size;
                const file = Math.round((fsize / 1024));
                // The size of the file.
                if (file > 2048) {
                    $('#error_msg').html('Copy Of Bussiness Operating Permit Sholud Be Less Than 2 MB!');
                    $('#error_msg').show();
                return false;
                }
            }
    }
    
    var fileInput = document.getElementById('copy_of_bussiness_operating_permit');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
    if (!allowedExtensions.exec(filePath)) {
    $('#error_msg').html('Copy Of Bussiness Operating Permit File Type Invalid!!');
    $('#error_msg').show();            
    return false;
    }
    
    var file_data = $('#copy_of_bussiness_operating_permit').prop('files')[0];
    var form_data = new FormData();
    form_data.append("file", file_data);
    //alert(form_data);
    $.ajax({
            type: 'post',
            url: 'index.php?path=account/applypezesha/pezeshafilestwo',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
            $('#copy_of_bussiness_operating_permit_button').prop('disabled', true);   
            },
            complete: function() {
            setInterval(function(){
            $('#copy_of_bussiness_operating_permit_button').prop('disabled', false);
            $('#copy_of_bussiness_operating_permit_button').html('UPLOAD');
            }, 1000);
            },
            success: function (response) {
            console.log(response);
            
            if(response.status == 200) {
            $('#copy_of_bussiness_operating_permit_button').html('<i class="fa fa-check" aria-hidden="true"></i>');   
            }
            
            if(response.status == 500) {
            $('#copy_of_bussiness_operating_permit_button').html('<i class="fa fa-times" aria-hidden="true"></i>');
            }
            
            }

    });
});
$('#copy_of_id_of_bussiness_owner_managing_director_button').on('click', function(e) {
    e.preventDefault();
    $('#success_msg').hide();
    $('#error_msg').hide();
    if( document.getElementById("copy_of_id_of_bussiness_owner_managing_director").files.length == 0 ){
    $('#error_msg').html('Copy Of ID Of Bussiness Owner / Managing Director Sholud Not Be Empty!');
    $('#error_msg').show();    
    return false;
    }
    
    const fi = document.getElementById('copy_of_id_of_bussiness_owner_managing_director');
    if (fi.files.length > 0) {
            for (var i = 0; i <= fi.files.length - 1; i++) {
  
                const fsize = fi.files.item(i).size;
                const file = Math.round((fsize / 1024));
                // The size of the file.
                if (file > 2048) {
                    $('#error_msg').html('Copy Of ID Of Bussiness Owner / Managing Director Sholud Be Less Than 2 MB!');
                    $('#error_msg').show();
                return false;
                }
            }
    }
    
    var fileInput = document.getElementById('copy_of_id_of_bussiness_owner_managing_director');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
    if (!allowedExtensions.exec(filePath)) {
    $('#error_msg').html('Copy Of ID Of Bussiness Owner / Managing Director File Type Invalid!!');
    $('#error_msg').show();            
    return false;
    }
    
    var file_data = $('#copy_of_id_of_bussiness_owner_managing_director').prop('files')[0];
    var form_data = new FormData();
    form_data.append("file", file_data);
    //alert(form_data);
    $.ajax({
            type: 'post',
            url: 'index.php?path=account/applypezesha/pezeshafilesthree',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
            $('#copy_of_id_of_bussiness_owner_managing_director_button').prop('disabled', true);   
            },
            complete: function() {
            setInterval(function(){
            $('#copy_of_id_of_bussiness_owner_managing_director_button').prop('disabled', false);
            $('#copy_of_id_of_bussiness_owner_managing_director_button').html('UPLOAD');
            }, 1000);
            },
            success: function (response) {
            console.log(response);
            
            if(response.status == 200) {
            $('#copy_of_id_of_bussiness_owner_managing_director_button').html('<i class="fa fa-check" aria-hidden="true"></i>');   
            }
            
            if(response.status == 500) {
            $('#copy_of_id_of_bussiness_owner_managing_director_button').html('<i class="fa fa-times" aria-hidden="true"></i>');
            }
            
            }

    });
});
$('#singlebutton').on('click', function(e) {
e.preventDefault();
$('#singlebutton').attr('data-terms', '1');
});

$('#cancelbutton').on('click', function(e) {
e.preventDefault();
$('#singlebutton').attr('data-terms', '0');
});

$('#submit_info_to_pezesha').on('click', function(e) {
    $('#success_msg').hide();
    $('#error_msg').hide(); 
    e.preventDefault();
    
    if($('#singlebutton').attr('data-terms') == '0' || $('#singlebutton').attr('data-terms') == ''){
    $('#error_msg').html('Accept Terms & Conditions!');
    $('#error_msg').show(); 
    return false;
    }
    
    if($("input[name=dob]").val() == ''){
    $('#error_msg').html('DOB Sholud Not Be Empty!');
    $('#error_msg').show(); 
    return false;
    }
    
    if($("input[name=kra]").val() == ''){
    $('#error_msg').html('KRA PIN Sholud Not Be Empty!');
    $('#error_msg').show(); 
    return false;
    }
    
    if($("input[name=national_id]").val() == ''){
    $('#error_msg').html('Nation ID Sholud Not Be Empty!');
    $('#error_msg').show(); 
    return false;
    }
    
    if($("input[name='gender']:checked").val() == ''){
    $('#error_msg').html('Please Select Gender!');
    $('#error_msg').show(); 
    return false;
    }
    
    if($("select[name='credit_period']").val() == ''){
    $('#error_msg').html('Please Select Credit Period!');
    $('#error_msg').show(); 
    return false;
    }
    
    $.ajax({
            type: 'post',
            url: 'index.php?path=account/applypezesha/updatecustomerinfo',
            data : { dob : $("input[name=dob]").val(), kra : $("input[name=kra]").val(), national_id : $("input[name=national_id]").val(), gender : $("input[name='gender']:checked").val(), credit_period : $("select[name='credit_period']").val() },
            dataType: 'json',
            cache: false,
            async: false,
            beforeSend: function() {
            $("#submit_info_to_pezesha").prop("disabled", true); 
            $('#submit_info_to_pezesha').html('<i class="fa fa-spinner" aria-hidden="true"></i>SUBMIT FOR CREDIT APPROVAL THROUGH PEZESHA');
            },
            complete: function() {
            $.ajax({
            type: 'post',
            url: 'index.php?path=account/applypezesha/userregistration',
            data : { credit_period : $("select[name='credit_period']").val() },
            dataType: 'json',
            cache: false,
            async: false,
            beforeSend: function() {
            },
            complete: function() {
            $.ajax({
            type: 'get',
            url: 'index.php?path=account/applypezesha/accrptterms',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
            console.log('accept terms response');
            console.log(response.data);
            console.log('accept terms response');
            }
            });    
            $.ajax({
            type: 'get',
            url: 'index.php?path=account/applypezesha/dataingestion',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
            },
            complete: function() {
            $("#submit_info_to_pezesha").prop("disabled", false); 
            $('#submit_info_to_pezesha').html('SUBMIT FOR CREDIT APPROVAL THROUGH PEZESHA');    
            },
            success: function (response) {
            console.log('data ingestion response');
            console.log(response);
            console.log('data ingestion response');
            }
            });    
            },
            success: function (response) {
            console.log('user registration response');
            if(response.data.status == 422) {
            console.log(response.errors);
            $.each(response.errors, function (key, data) {
            $('#error_msg').html(data);
            $('#error_msg').show();
            })
            }
            
            if(response.data.status == 200) {    
            $('#success_msg').html('You have been successfully registred for pezesha credit. Your application is under review.');
            $('#success_msg').show();
            
            $("#dob").prop("readonly", true);
            $("#kra").prop("readonly", true);
            $("#gender").prop("readonly", true);
            $("#national_id").prop("readonly", true);
            
            $("#copy_of_certificate_of_incorporation").prop("disabled", true);
            $("#copy_of_bussiness_operating_permit").prop("disabled", true);
            $("#copy_of_id_of_bussiness_owner_managing_director").prop("disabled", true);
            
            $("#copy_of_certificate_of_incorporation_button").prop("disabled", true);
            $("#copy_of_bussiness_operating_permit_button").prop("disabled", true);
            $("#copy_of_id_of_bussiness_owner_managing_director_button").prop("disabled", true);
            
            $("#submit_info_to_pezesha").prop("disabled", true);
            
            setInterval(function(){ window.location.replace('/'); }, 10000);
            }
            console.log(response);
            console.log('user registration response');
            }

            });    
            },
            success: function (response) {
            console.log('updatecustomerinfo response');
            console.log(response);
            console.log('updatecustomerinfo response');
            }
    });
});

/*$('#parent_terms_conditions').scroll(function() {
  console.log('SCROLLING');
  var disable = $('#terms_conditions').height() != ($(this).scrollTop() + $(this).height());
  $('#singlebutton').prop('disabled', disable);
});*/

</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript">
$('.date').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
});
</script>
<style>
    .option_pay {
        margin-top:-3px !important;
    }     
</style>
</body>
</html>
