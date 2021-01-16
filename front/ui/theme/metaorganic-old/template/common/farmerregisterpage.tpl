<!DOCTYPE html>
<html lang="en">
<head>
<!--<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Farmer Registration Page</title>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no">
<meta name="description" content="Default Description">
<meta name="keywords" content="fashion, store, E-commerce">
<meta name="robots" content="*">
<meta name="viewport" content="initial-scale=1.0, width=device-width">
<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
<link rel="icon" href="images/favicon.png" type="image/x-icon">-->

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="kdt:page" content="home-page"> 

<meta http-equiv="content-language" content="<?= $config_language?>">
    
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<title><?= $heading_title ?></title>
<?php if ($icon) { ?>
        <link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
	
<link rel="shortcut icon" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/favicon.png" type="image/x-icon">
<link rel="icon" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/favicon.png" type="image/x-icon">


<!-- CSS Style -->

<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/font-awesome.css" media="all">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/revslider.css" >
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/owl.theme.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/jquery.bxslider.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/jquery.mobile-menu.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/style.css" media="all">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/responsive.css" media="all">

<link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:700,600,800,400' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i,900" rel="stylesheet">


</head>

<body>

<div id="page">

  <header>
    
    <div id="header">
      <div class="container">
        <div class="header-container row">
          <div class="logo"> <a class="base_url" href="<?php echo BASE_URL;?>" title="index">
            <div><img src="<?=$logo?>" alt="logo"></div>
            </a> </div>
          <div class="fl-nav-menu">
            <nav>
              <div class="mm-toggle-wrap">
                <div class="mm-toggle"><i class="icon-align-justify"></i><span class="mm-label">Menu</span> </div>
              </div>
              <div class="nav-inner"> 
                <!-- BEGIN NAV -->
                <ul id="nav" class="hidden-xs">
                  <!--<li  data-link ="home"> <a class="level-top" ><span>Home</span></a></li> -->
                  <li  data-link ="about"> <a href="<?= BASE_URL;?>#about"class="level-top"  ><span>About Us</span></a> </li>
                  <li data-link ="whom"> <a href="<?= BASE_URL;?>#whom" class="level-top" ><span>Who We Serve</span></a> </li>
                  <li data-link ="works"> <a href="<?= BASE_URL;?>#works" class="level-top"><span>How It Works</span></a> </li>
                  
                  <li data-link ="contact"> <a href="<?= BASE_URL;?>#contact" class="level-top"><span>Contact Us</span></a> </li>
                </ul>
                <!--nav--> 
              </div>
            </nav>
          </div>
          
          <!--row-->
          
          <div class="fl-header-right">
            <div class="fl-links">
               <div class="no-js clicker"> 
               
              </div>
            </div>
            <!--mini-cart-->
            
            <!--links--> 
          </div>
        </div>
      </div>
    </div>
  </header>


    <div class="page-headingnew">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
        <div class="page-title">
<h2 class="font-white mt20">Farmer Registration</h2>
</div>
        </div>
      </div>
    </div>
  </div>
  <!-- BEGIN Main Container col2-right -->
  <div class="main-container col2-right-layout">
    <div class="main container">
      <div class="row">
        <div class="col-main col-sm-12 wow bounceInUp animated animated" style="visibility: visible;">
         <div id="signup-message" style="text-align:center;font-size:20px;margin-top:20px;">
      </div>

          <div id="messages_product_view"></div>
          <form action="<?php echo $action; ?>" id="registerForm" method="post"  autocomplete="off"  enctype="multipart/form-data"  class="form">
            <div class="static-contain">
              <fieldset class="group-select">
                <ul>
                  <li id="billing-new-address-form">
                    <fieldset class="">
                      <ul>
                        <li>
                          <div class="customer-name">
                            <div class="input-box name-firstname">
                              <label for="name"><em class="required">*</em>Name</label>
                              <br>
                              <input name="name" id="name" title="Name" placeholder="john doe" class="input-text required-entry" type="text">
                            </div>
                            <div class="input-box name-firstname">
                              <label for="email"><em class="required"></em>Email Id</label>
                              <br>
                              <input name="email" id="email" title="Email" placeholder="john.doe@gmail.com" class="input-text required-entry validate-email" type="text">
                            </div>
                          </div>
                        </li>
                        <li>
                            <label for="Address"><em class="required">*</em>Farm/Residential Address</label>
                            <br>
                            <textarea name="Address" id="address" title="Address" placeholder="Address" class="required-entry input-text" cols="5" rows="3"></textarea>
                          </li>
                          <li>
                            <div class="customer-name">
                             <!-- <div class="input-box name-firstname">
                                <label for="telephone"><em class="required">* </em>Contact Number</label>
                                <br>
                                <input name="telephone" id="telephone" placeholder="Telephone" value="" class="input-text" type="number">
                              </div>-->
                            

                               <div class="input-box name-phone">
                              <label for="email"><em class="required">*</em><?= $entry_phone ?></label>
                             <br>
								<span class="input-group-btn" style="
								display: table;
								position: relative;
								margin-bottom: -46px;">

								<p id="button-reward" class="" style="padding: 12px 13px;border-radius: 20px 1px 1px 20px;font-size: 15px;font-weight: 600;color: #fff;background-color: #522e5b;border-color: #522e5b;display: inline-block;margin-bottom: 0;font-size: 14px;line-height: 1.42857143;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;margin-right: -1px;">

								<font style="vertical-align: inherit;">
								<font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
								+<?= $this->config->get('config_telephone_code') ?>                                               
								</font></font></font>
								</font>
								</p>

								</span>
							 
							 <input id="register_phone_number" autocomplete="off"  name="telephone" type="text" class="input-text input-md" required="" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9">
                            </div>

                            <div class="input-box name-firstname">
      <label for="farmertype">Farmer Type</label>
      <br>
          <select name="farmertype" id="farmertype" class="validate-select" title="farmertype">
           <option value="Commercial">Commercial</option> 
           <option value="Smallholder">Smallholder</option> 
           <option value="Subsistence">Subsistence</option> 
           </select> 
 </div>

                              
                            </div>
                          </li>
                          <li>
                                <div class="customer-name">
                                        <div class="input-box name-firstname">
                                         <label for="country" ><em class="required">*</em>Country</label>
                                          <br>
                                          <select name="country_id" id="country" class="validate-select" title="Country">
                                              <option value=""> </option><option value="AF">Afghanistan</option>
                                              <option value="AX">Åland Islands</option>
                                              <option value="AL">Albania</option>
                                              <option value="DZ">Algeria</option>
                                              <option value="AS">American Samoa</option>
                                              <option value="AD">Andorra</option>
                                              <option value="AO">Angola</option>
                                              <option value="AI">Anguilla</option>
                                              <option value="AQ">Antarctica</option>
                                              <option value="AG">Antigua and Barbuda</option>
                                              <option value="AR">Argentina</option>
                                              <option value="AM">Armenia</option>
                                              <option value="AW">Aruba</option>
                                              <option value="AU">Australia</option>
                                              <option value="AT">Austria</option>
                                              <option value="AZ">Azerbaijan</option>
                                              <option value="BS">Bahamas</option>
                                              <option value="BH">Bahrain</option>
                                              <option value="BD">Bangladesh</option>
                                              <option value="BB">Barbados</option>
                                              <option value="BY">Belarus</option>
                                              <option value="BE">Belgium</option>
                                              <option value="BZ">Belize</option>
                                              <option value="BJ">Benin</option>
                                              <option value="BM">Bermuda</option>
                                              <option value="BT">Bhutan</option>
                                              <option value="BO">Bolivia</option>
                                              <option value="BA">Bosnia and Herzegovina</option>
                                              <option value="BW">Botswana</option>
                                              <option value="BV">Bouvet Island</option>
                                              <option value="BR">Brazil</option>
                                              <option value="IO">British Indian Ocean Territory</option>
                                              <option value="VG">British Virgin Islands</option>
                                              <option value="BN">Brunei</option>
                                              <option value="BG">Bulgaria</option>
                                              <option value="BF">Burkina Faso</option>
                                              <option value="BI">Burundi</option>
                                              <option value="KH">Cambodia</option>
                                              <option value="CM">Cameroon</option>
                                              <option value="CA">Canada</option>
                                              <option value="CV">Cape Verde</option>
                                              <option value="KY">Cayman Islands</option>
                                              <option value="CF">Central African Republic</option>
                                              <option value="TD">Chad</option>
                                              <option value="CL">Chile</option>
                                              <option value="CN">China</option>
                                              <option value="CX">Christmas Island</option>
                                              <option value="CC">Cocos [Keeling] Islands</option>
                                              <option value="CO">Colombia</option>
                                              <option value="KM">Comoros</option>
                                              <option value="CG">Congo - Brazzaville</option>
                                              <option value="CD">Congo - Kinshasa</option>
                                              <option value="CK">Cook Islands</option>
                                              <option value="CR">Costa Rica</option>
                                              <option value="CI">Côte d’Ivoire</option>
                                              <option value="HR">Croatia</option>
                                              <option value="CU">Cuba</option>
                                              <option value="CY">Cyprus</option>
                                              <option value="CZ">Czech Republic</option>
                                              <option value="DK">Denmark</option>
                                              <option value="DJ">Djibouti</option>
                                              <option value="DM">Dominica</option>
                                              <option value="DO">Dominican Republic</option>
                                              <option value="EC">Ecuador</option>
                                              <option value="EG">Egypt</option>
                                              <option value="SV">El Salvador</option>
                                              <option value="GQ">Equatorial Guinea</option>
                                              <option value="ER">Eritrea</option>
                                              <option value="EE">Estonia</option>
                                              <option value="ET">Ethiopia</option>
                                              <option value="FK">Falkland Islands</option>
                                              <option value="FO">Faroe Islands</option>
                                              <option value="FJ">Fiji</option>
                                              <option value="FI">Finland</option>
                                              <option value="FR">France</option>
                                              <option value="GF">French Guiana</option>
                                              <option value="PF">French Polynesia</option>
                                              <option value="TF">French Southern Territories</option>
                                              <option value="GA">Gabon</option>
                                              <option value="GM">Gambia</option>
                                              <option value="GE">Georgia</option>
                                              <option value="DE">Germany</option>
                                              <option value="GH">Ghana</option>
                                              <option value="GI">Gibraltar</option>
                                              <option value="GR">Greece</option>
                                              <option value="GL">Greenland</option>
                                              <option value="GD">Grenada</option>
                                              <option value="GP">Guadeloupe</option>
                                              <option value="GU">Guam</option>
                                              <option value="GT">Guatemala</option>
                                              <option value="GG">Guernsey</option>
                                              <option value="GN">Guinea</option>
                                              <option value="GW">Guinea-Bissau</option>
                                              <option value="GY">Guyana</option>
                                              <option value="HT">Haiti</option>
                                              <option value="HM">Heard Island and McDonald Islands</option>
                                              <option value="HN">Honduras</option>
                                              <option value="HK">Hong Kong SAR China</option>
                                              <option value="HU">Hungary</option>
                                              <option value="IS">Iceland</option>
                                              <option value="IN">India</option>
                                              <option value="ID">Indonesia</option>
                                              <option value="IR">Iran</option>
                                              <option value="IQ">Iraq</option>
                                              <option value="IE">Ireland</option>
                                              <option value="IM">Isle of Man</option>
                                              <option value="IL">Israel</option>
                                              <option value="IT">Italy</option>
                                              <option value="JM">Jamaica</option>
                                              <option value="JP">Japan</option>
                                              <option value="JE">Jersey</option>
                                              <option value="JO">Jordan</option>
                                              <option value="KZ">Kazakhstan</option>
                                              <option value="KE">Kenya</option>
                                              <option value="KI">Kiribati</option>
                                              <option value="KW">Kuwait</option>
                                              <option value="KG">Kyrgyzstan</option>
                                              <option value="LA">Laos</option>
                                              <option value="LV">Latvia</option>
                                              <option value="LB">Lebanon</option>
                                              <option value="LS">Lesotho</option>
                                              <option value="LR">Liberia</option>
                                              <option value="LY">Libya</option>
                                              <option value="LI">Liechtenstein</option>
                                              <option value="LT">Lithuania</option>
                                              <option value="LU">Luxembourg</option>
                                              <option value="MO">Macau SAR China</option>
                                              <option value="MK">Macedonia</option>
                                              <option value="MG">Madagascar</option>
                                              <option value="MW">Malawi</option>
                                              <option value="MY">Malaysia</option>
                                              <option value="MV">Maldives</option>
                                              <option value="ML">Mali</option>
                                              <option value="MT">Malta</option>
                                              <option value="MH">Marshall Islands</option>
                                              <option value="MQ">Martinique</option>
                                              <option value="MR">Mauritania</option>
                                              <option value="MU">Mauritius</option>
                                              <option value="YT">Mayotte</option>
                                              <option value="MX">Mexico</option>
                                              <option value="FM">Micronesia</option>
                                              <option value="MD">Moldova</option>
                                              <option value="MC">Monaco</option>
                                              <option value="MN">Mongolia</option>
                                              <option value="ME">Montenegro</option>
                                              <option value="MS">Montserrat</option>
                                              <option value="MA">Morocco</option>
                                              <option value="MZ">Mozambique</option>
                                              <option value="MM">Myanmar [Burma]</option>
                                              <option value="NA">Namibia</option>
                                              <option value="NR">Nauru</option>
                                              <option value="NP">Nepal</option>
                                              <option value="NL">Netherlands</option>
                                              <option value="AN">Netherlands Antilles</option>
                                              <option value="NC">New Caledonia</option>
                                              <option value="NZ">New Zealand</option>
                                              <option value="NI">Nicaragua</option>
                                              <option value="NE">Niger</option>
                                              <option value="NG">Nigeria</option>
                                              <option value="NU">Niue</option>
                                              <option value="NF">Norfolk Island</option>
                                              <option value="MP">Northern Mariana Islands</option>
                                              <option value="KP">North Korea</option>
                                              <option value="NO">Norway</option>
                                              <option value="OM">Oman</option>
                                              <option value="PK">Pakistan</option>
                                              <option value="PW">Palau</option>
                                              <option value="PS">Palestinian Territories</option>
                                              <option value="PA">Panama</option>
                                              <option value="PG">Papua New Guinea</option>
                                              <option value="PY">Paraguay</option>
                                              <option value="PE">Peru</option>
                                              <option value="PH">Philippines</option>
                                              <option value="PN">Pitcairn Islands</option>
                                              <option value="PL">Poland</option>
                                              <option value="PT">Portugal</option>
                                              <option value="PR">Puerto Rico</option>
                                              <option value="QA">Qatar</option>
                                              <option value="RE">Réunion</option>
                                              <option value="RO">Romania</option>
                                              <option value="RU">Russia</option>
                                              <option value="RW">Rwanda</option>
                                              <option value="BL">Saint Barthélemy</option>
                                              <option value="SH">Saint Helena</option>
                                              <option value="KN">Saint Kitts and Nevis</option>
                                              <option value="LC">Saint Lucia</option>
                                              <option value="MF">Saint Martin</option>
                                              <option value="PM">Saint Pierre and Miquelon</option>
                                              <option value="VC">Saint Vincent and the Grenadines</option>
                                              <option value="WS">Samoa</option>
                                              <option value="SM">San Marino</option>
                                              <option value="ST">São Tomé and Príncipe</option>
                                              <option value="SA">Saudi Arabia</option>
                                              <option value="SN">Senegal</option>
                                              <option value="RS">Serbia</option>
                                              <option value="SC">Seychelles</option>
                                              <option value="SL">Sierra Leone</option>
                                              <option value="SG">Singapore</option>
                                              <option value="SK">Slovakia</option>
                                              <option value="SI">Slovenia</option>
                                              <option value="SB">Solomon Islands</option>
                                              <option value="SO">Somalia</option>
                                              <option value="ZA">South Africa</option>
                                              <option value="GS">South Georgia and the South Sandwich Islands</option>
                                              <option value="KR">South Korea</option>
                                              <option value="ES">Spain</option>
                                              <option value="LK">Sri Lanka</option>
                                              <option value="SD">Sudan</option>
                                              <option value="SR">Suriname</option>
                                              <option value="SJ">Svalbard and Jan Mayen</option>
                                              <option value="SZ">Swaziland</option>
                                              <option value="SE">Sweden</option>
                                              <option value="CH">Switzerland</option>
                                              <option value="SY">Syria</option>
                                              <option value="TW">Taiwan</option>
                                              <option value="TJ">Tajikistan</option>
                                              <option value="TZ">Tanzania</option>
                                              <option value="TH">Thailand</option>
                                              <option value="TL">Timor-Leste</option>
                                              <option value="TG">Togo</option>
                                              <option value="TK">Tokelau</option>
                                              <option value="TO">Tonga</option>
                                              <option value="TT">Trinidad and Tobago</option>
                                              <option value="TN">Tunisia</option>
                                              <option value="TR">Turkey</option>
                                              <option value="TM">Turkmenistan</option>
                                              <option value="TC">Turks and Caicos Islands</option>
                                              <option value="TV">Tuvalu</option>
                                              <option value="UG">Uganda</option>
                                              <option value="UA">Ukraine</option>
                                              <option value="AE">United Arab Emirates</option>
                                              <option value="GB">United Kingdom</option>
                                              <option value="US" selected="selected">United States</option>
                                              <option value="UY">Uruguay</option>
                                              <option value="UM">U.S. Minor Outlying Islands</option>
                                              <option value="VI">U.S. Virgin Islands</option>
                                              <option value="UZ">Uzbekistan</option>
                                              <option value="VU">Vanuatu</option>
                                              <option value="VA">Vatican City</option>
                                              <option value="VE">Venezuela</option>
                                              <option value="VN">Vietnam</option>
                                              <option value="WF">Wallis and Futuna</option>
                                              <option value="EH">Western Sahara</option>
                                              <option value="YE">Yemen</option>
                                              <option value="ZM">Zambia</option>
                                              <option value="ZW">Zimbabwe</option>
                                            </select> 
                                        </div>
                                        <div class="input-box name-firstname">
                                                <label for="town"><em class="required">*</em>Town/ Village</label>
                                                <br>
                                          <input name="town" id="town" title="town" placeholder="Town" class="input-text required-entry" type="text">
                                              
                                              </div>
                                      </div>
                            
                        </li>

                        <li>
                            <div class="customer-name">
                              <div class="input-box name-firstname">
                                <label for="businessentity">Business Entity</label>
                                <br>
                                

                                 <select name="businessentity" id="businessentity" class="validate-select" title="businessentity">
                                              <option value="Sole Proprietor">Sole Proprietor </option>
                                              <option value="Partnerships">Partnerships</option>
                                              <option value="Close Corporation">Close Corporation</option>
                                              <option value="Private Company">Private Company</option>
                                                <option value="Public Company">Public Company</option>  
                                                <option value="Co-operatives">Co-operatives</option>
                                                  <option value="Trusts">Trusts</option>
                                                    <option value="Not Registered">Not Registered</option>

                                              </select>
                              </div>
                              <div class="input-box name-firstname">
                                <label for="nameoffarm"><em class="required"> </em> Name of farm</label>
                                <br>
                                <input name="nameoffarm"  title="farm" placeholder="Name of Farm" class="input-text required-entry validate-email" type="text">
                              </div>
                            </div>
                          </li> 
                        <li>
                                <div class="customer-name">
                                        <div class="input-box name-firstname">
                                         <label for="Total" ><em class="required"></em>Total Arable Area</label>
                                          <br>
                                          <input name="Total" id="Total" title="Total" placeholder="Total Arable Area" class="input-text required-entry" type="text">
                                        </div>
                                        <div class="input-box name-firstname">
                                                <label for="Crop"><em class="required"></em>Crop Type</label>
                                                <br>
                                                <select name="Crop" id="Crop" class="validate-select" title="Crop">
                                                    
                                                    <option value="Pe">Perishable</option>
                                                    <option value="NPe">Non-Perishable</option>
                                                    <option value="Gl">Green Leafs</option>
                                                    <option value="He">Herbs</option>
                                                    
                                                  </select> 
                                              </div>
                                      </div>
                            
                        </li>


                         <li>
                            <div class="customer-name">
                              <div class="input-box name-firstname">
                                <label for="cropproduce">Approx Crop Produce (in tons)</label>
                                <br>
                                <input name="cropproduce" id="cropproduce" placeholder="Crop Produce" value="" class="input-text" type="text">
                              </div>
                             <div class="input-box name-firstname">
                                <label for="farm"><em class="required"> </em> Work on farm</label>
                                <br>
                                
                                  <select name="farm" id="farm" class="validate-select" title="farm">
                                             <option value="Full Time">Full Time</option>
                                              <option value="Part Time">Part Time</option>
                                              </select>
                              </div>
                            </div>
                          </li>
                          <li>
                            <div class="customer-name">
                              
                              <div class="input-box name-firstname">
                                <label for="sellproduce"><em class="required"> </em> Currently How do you sell your produce? </label>
                                <br>
                                  <select name="sellproduce" id="sellproduce" class="validate-select" title="sellproduce">
                                                    
                                                    <option value="online">online</option> 
                                                    <option value="Local Market">Local Market</option> 
                                                    <option value="Traders">Traders</option> 
                                                    <option value="Export">Export</option> 
                                                    <option value="Others">Others</option> 


                                                    
                                                  </select> 
                              </div>
                            </div>
                          </li>
                           <li>
                            <label for="cropsgrown">Crops Grown</label>
                            <br>
                            <textarea name="cropsgrown" id="cropsgrown" title="cropsgrown" placeholder="crops grown" class="required-entry input-text" cols="5" rows="3"></textarea>
                          </li>

                        <li>
                            <div class="customer-name">
                             
                              <div >
                              <?php if ($site_key) { ?>
                                <label for="input-date-added"></label>
                                <br>
                                  <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>" style="padding-left:16px; margin-left: 35%;"></div>
								                  <div style="display:none;"class="text-danger"id="error_captha" >Please Validate Captha</div>
                               <?php } ?>
                              </div>
                            </div>
                          </li>
                         <div style="text-align:center; margin:0px auto;">
                  <p class="require"><em class="required">* </em>Required Fields</p>
                   
                  <div class="buttons-set">
                    <button  id="registerfarmer" type="button" title="Submit" class="button submit"><span><span style="font-size:20px;">Submit</span></span></button>
                  </div>
                 </div>
                  
                </ul>
              </fieldset>
            </div>
          </form>
          
        </div>
       
        <!--col-right sidebar--> 
      </div>
      <!--row--> 
    </div>
    <!--main-container-inner--> 
  </div>
  <!--main-container col2-left-layout--> 
  
 
  <div class="container">
    <div class="row our-features-box">
      <ul>
        <li>
          <div class="feature-box">
            <div class="icon-truck"></div>
            <div class="content">FREE SHIPPING </div>
          </div>
        </li>
        <li>
          <div class="feature-box">
            <div class="icon-support"></div>
            <div class="content">Have a question?<br>
              +254 780 703 586</div>
          </div>
        </li>
        <li>
          <div class="feature-box">
            <div class="icon-money"></div>
            <div class="content">Customized Discounts & Pricing</div>
          </div>
        </li>
        <li>
          <div class="feature-box">
            <div class="icon-return"></div>
            <div class="content">Easy Return Policy</div>
          </div>
        </li>
        <li class="last">
          <div class="feature-box android-app">  <a href="https://play.google.com/store/apps/details?id=com.kwikbasket.customer"><i class="fa fa-android"></i> download</a> </div>
        </li>
      </ul>
    </div>
  </div>
 
  <!-- For version 1,2,3,4,6 -->
  
   
   <footer> 

    
    <!--footer-middle-->
    <div class="footer-top">
      <div class="container">
        <div class="row">
          <div class="col-xs-12 col-sm-4">
            <div class="social">
              <ul>
                <li class="fb"><a href="https://www.facebook.com/kwikbasket" target="_blank"></a></li>
                <li class="tw"><a href="#"></a></li>
                <li class="linkedin"><a href="#"></a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-4 col-xs-12 coppyright"> © 2020 Kwik Baskets. All Rights Reserved. </div>
          <div class="col-xs-12 col-sm-4">
            <div class="payment-accept"> <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/payment-1.png" alt=""> <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/payment-2.png" alt=""> <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/payment-3.png" alt=""> </div>
          </div>
        </div>
      </div>
    </div>
    
    
    <!--footer-bottom--> 
    <!-- BEGIN SIMPLE FOOTER --> 
  </footer>
  <!-- End For version 1,2,3,4,6 --> 
  
</div>
<!--page--> 
<!-- Mobile Menu-->



<!-- JavaScript --> 


<script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/bootstrap.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/parallax.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/revslider.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/common.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/jquery.bxslider.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/owl.carousel.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/jquery.mobile-menu.min.js"></script> 

<script src="<?= $base;?>front/ui/theme/metaorganic/javascript/common.js?v=2.0.7" type="text/javascript"></script>
 <script src="https://www.google.com/recaptcha/api.js" type="text/javascript"></script>

</body>
</html>

<script>

jQuery('input[name="telephone"]').keyup(function(e)
                                {
  if (/\D/g.test(this.value))
  {
    // Filter non-digits from input value.
    this.value = this.value.replace(/\D/g, '');
  }
});

$("#name").keyup(function(){
    
  $('#signup-message').html('<p>  </p>');

});


</script>