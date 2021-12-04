
 <link rel="stylesheet" type="text/css"
    href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/iziToast.min.css">
<script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/iziToast.min.js" async
    defer="defer"></script>


<div class="feedbackModal_popup">
<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close rightalign" data-dismiss="modal" aria-label="Close">
                        
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
<path d="M10 18C5.59 18 2 14.41 2 10C2 5.59 5.59 2 10 2C14.41 2 18 5.59 18 10C18 14.41 14.41 18 10 18ZM10 0C4.47 0 0 4.47 0 10C0 15.53 4.47 20 10 20C15.53 20 20 15.53 20 10C20 4.47 15.53 0 10 0ZM12.59 6L10 8.59L7.41 6L6 7.41L8.59 10L6 12.59L7.41 14L10 11.41L12.59 14L14 12.59L11.41 10L14 7.41L12.59 6Z" fill="#FF8888"/>
</svg>
                        </button>  <div class="store-find-block">
                        
                        <div class="store-find">
                            <div class="store-head">
                            <div class="row">
                                <span class="v28_4">Let us know your feedback</span></div>
                                <div class="row">
                                <span class="v28_6">Your valuable feedback helps us to serve you better</span></div>
                            
                           
                            </div>
                              <div class="row">
                            <div class="v34_63">
      </div></div>
                           
                            <div id="feedback-message">
                            </div>
                            <div id="feedback-success-message" style="color: green">
                            </div>
                            <!-- Text input-->
                            <!--<div class="store-form">-->
                                
                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="feedback-form">
                            <fieldset>
                             

  <div class="form-group" >
                             How was your overall shopping experience?
                             </div

                             <div class="form-group">
                             <div class="emoji-container">
        <svg width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path class="emoji" id="emoji1" onclick="getRating(1)" d="M20 0C8.94004 0 3.8147e-05 9 3.8147e-05 20C3.8147e-05 31 8.94004 40 20 40C31 40 40 31 40 20C40 9 31 0 20 0ZM27 12C28.66 12 30 13.34 30 15C30 16.66 28.66 18 27 18C25.34 18 24 16.66 24 15C24 13.34 25.34 12 27 12ZM13 12C14.66 12 16 13.34 16 15C16 16.66 14.66 18 13 18C11.34 18 10 16.66 10 15C10 13.34 11.34 12 13 12ZM9.78004 30C11.38 25.92 15.34 23 20 23C24.66 23 28.62 25.92 30.22 30H9.78004Z" fill="#C4C4C4"></path>
        </svg>
        <svg width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path class="emoji" id="emoji2" onclick="getRating(2)" d="M20 0C16.0444 0 12.1776 1.17298 8.88864 3.37061C5.59966 5.56824 3.03621 8.69181 1.52246 12.3463C0.00870383 16.0009 -0.387363 20.0222 0.384342 23.9018C1.15605 27.7814 3.06086 31.3451 5.85791 34.1421C8.65496 36.9392 12.2186 38.844 16.0982 39.6157C19.9779 40.3874 23.9992 39.9913 27.6537 38.4776C31.3082 36.9638 34.4318 34.4004 36.6294 31.1114C38.8271 27.8224 40 23.9556 40 20C40 17.3736 39.4827 14.7728 38.4776 12.3463C37.4725 9.91982 35.9994 7.71504 34.1422 5.85787C32.285 4.00069 30.0802 2.5275 27.6537 1.52241C25.2272 0.517315 22.6265 0 20 0V0ZM13 12C13.5934 12 14.1734 12.1759 14.6668 12.5056C15.1601 12.8352 15.5446 13.3038 15.7717 13.852C15.9987 14.4001 16.0582 15.0033 15.9424 15.5853C15.8266 16.1672 15.5409 16.7018 15.1214 17.1213C14.7018 17.5409 14.1673 17.8266 13.5853 17.9424C13.0034 18.0581 12.4002 17.9987 11.852 17.7716C11.3038 17.5446 10.8353 17.1601 10.5056 16.6667C10.176 16.1734 10 15.5933 10 15C10.0203 14.2107 10.3429 13.4594 10.9011 12.9011C11.4594 12.3428 12.2108 12.0202 13 12V12ZM30 28H22C20.5938 27.9979 19.2118 28.3666 17.9934 29.0688C16.775 29.7711 15.7632 30.7821 15.06 32L11.6 30C12.6539 28.1746 14.17 26.6591 15.9957 25.6058C17.8214 24.5525 19.8923 23.9987 22 24H30V28ZM27 18C26.4067 18 25.8267 17.8241 25.3333 17.4944C24.84 17.1648 24.4555 16.6962 24.2284 16.1481C24.0013 15.5999 23.9419 14.9967 24.0577 14.4147C24.1734 13.8328 24.4592 13.2982 24.8787 12.8787C25.2983 12.4591 25.8328 12.1734 26.4148 12.0576C26.9967 11.9419 27.5999 12.0013 28.1481 12.2284C28.6963 12.4554 29.1648 12.8399 29.4945 13.3333C29.8241 13.8266 30 14.4067 30 15C29.9798 15.7893 29.6572 16.5406 29.099 17.0989C28.5407 17.6572 27.7893 17.9798 27 18V18Z" fill="#C4C4C4"></path>
        </svg>
        <svg width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path class="emoji" id="emoji3" onclick="getRating(3)" d="M20 0C17.3735 0 14.7728 0.517315 12.3463 1.52241C9.9198 2.5275 7.71502 4.00069 5.85785 5.85786C2.10712 9.60859 -1.90735e-05 14.6957 -1.90735e-05 20C-1.90735e-05 25.3043 2.10712 30.3914 5.85785 34.1421C7.71502 35.9993 9.9198 37.4725 12.3463 38.4776C14.7728 39.4827 17.3735 40 20 40C25.3043 40 30.3914 37.8929 34.1421 34.1421C37.8928 30.3914 40 25.3043 40 20C40 17.3736 39.4827 14.7728 38.4776 12.3463C37.4725 9.91982 35.9993 7.71504 34.1421 5.85786C32.2849 4.00069 30.0802 2.5275 27.6537 1.52241C25.2271 0.517315 22.6264 0 20 0ZM9.99998 15C9.99998 14.2044 10.3161 13.4413 10.8787 12.8787C11.4413 12.3161 12.2043 12 13 12C13.7956 12 14.5587 12.3161 15.1213 12.8787C15.6839 13.4413 16 14.2044 16 15C16 15.7956 15.6839 16.5587 15.1213 17.1213C14.5587 17.6839 13.7956 18 13 18C12.2043 18 11.4413 17.6839 10.8787 17.1213C10.3161 16.5587 9.99998 15.7956 9.99998 15ZM28 28H12V24H28V28ZM27 18C26.2043 18 25.4413 17.6839 24.8787 17.1213C24.3161 16.5587 24 15.7956 24 15C24 14.2044 24.3161 13.4413 24.8787 12.8787C25.4413 12.3161 26.2043 12 27 12C27.7956 12 28.5587 12.3161 29.1213 12.8787C29.6839 13.4413 30 14.2044 30 15C30 15.7956 29.6839 16.5587 29.1213 17.1213C28.5587 17.6839 27.7956 18 27 18Z" fill="#C4C4C4"></path>
        </svg>
        <svg width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path class="emoji" id="emoji4" onclick="getRating(4)" d="M20 0C17.3735 0 14.7728 0.517315 12.3463 1.52241C9.9198 2.5275 7.71502 4.00069 5.85785 5.85786C2.10712 9.60859 -1.52588e-05 14.6957 -1.52588e-05 20C-1.52588e-05 25.3043 2.10712 30.3914 5.85785 34.1421C7.71502 35.9993 9.9198 37.4725 12.3463 38.4776C14.7728 39.4827 17.3735 40 20 40C25.3043 40 30.3914 37.8929 34.1421 34.1421C37.8928 30.3914 40 25.3043 40 20C40 17.3736 39.4827 14.7728 38.4776 12.3463C37.4725 9.91982 35.9993 7.71504 34.1421 5.85786C32.2849 4.00069 30.0802 2.5275 27.6537 1.52241C25.2271 0.517315 22.6264 0 20 0V0ZM9.99998 15C9.99998 13.4 11.4 12 13 12C14.6 12 16 13.4 16 15C16 16.6 14.6 18 13 18C11.4 18 9.99998 16.6 9.99998 15ZM20 30.46C16.5 30.46 13.42 29 11.62 26.84L14.46 24C15.36 25.44 17.5 26.46 20 26.46C22.5 26.46 24.64 25.44 25.54 24L28.38 26.84C26.58 29 23.5 30.46 20 30.46ZM27 18C25.4 18 24 16.6 24 15C24 13.4 25.4 12 27 12C28.6 12 30 13.4 30 15C30 16.6 28.6 18 27 18Z" fill="#C4C4C4"></path>
        </svg>
        <svg width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path class="emoji" id="emoji5" onclick="getRating(5)" d="M20 0C8.93998 0 -1.52588e-05 9 -1.52588e-05 20C-1.52588e-05 25.3043 2.10712 30.3914 5.85785 34.1421C7.71502 35.9993 9.9198 37.4725 12.3463 38.4776C14.7728 39.4827 17.3735 40 20 40C25.3043 40 30.3914 37.8929 34.1421 34.1421C37.8928 30.3914 40 25.3043 40 20C40 17.3736 39.4827 14.7728 38.4776 12.3463C37.4725 9.91982 35.9993 7.71504 34.1421 5.85786C32.2849 4.00069 30.0802 2.5275 27.6537 1.52241C25.2271 0.517315 22.6264 0 20 0ZM27 12C27.7956 12 28.5587 12.3161 29.1213 12.8787C29.6839 13.4413 30 14.2044 30 15C30 15.7956 29.6839 16.5587 29.1213 17.1213C28.5587 17.6839 27.7956 18 27 18C26.2043 18 25.4413 17.6839 24.8787 17.1213C24.3161 16.5587 24 15.7956 24 15C24 14.2044 24.3161 13.4413 24.8787 12.8787C25.4413 12.3161 26.2043 12 27 12ZM13 12C13.7956 12 14.5587 12.3161 15.1213 12.8787C15.6839 13.4413 16 14.2044 16 15C16 15.7956 15.6839 16.5587 15.1213 17.1213C14.5587 17.6839 13.7956 18 13 18C12.2043 18 11.4413 17.6839 10.8787 17.1213C10.3161 16.5587 9.99998 15.7956 9.99998 15C9.99998 14.2044 10.3161 13.4413 10.8787 12.8787C11.4413 12.3161 12.2043 12 13 12ZM20 31C15.34 31 11.38 28.08 9.77998 24H30.22C28.6 28.08 24.66 31 20 31Z" fill="#C4C4C4"></path>
        </svg>     
                    <input id="rating_id" name="rating_id" type="hidden" class="form-control input-md">
                    <input id="feedback_type" name="feedback_type" type="hidden" class="form-control input-md">

     

                             </div>
                                <div class="form-group" id="modal_bodyvalue">
                                   <!-- <label class="col-sm-12 control-label orderlabel super" style="background: #FFE4CB;text-align: center;padding-top: 0px" for="input-name">Order Id: <text id="modal_bodyvalue"></text></label>-->
                                    
                                </div>

                                
                               
                                <div class="form-group required">
                                    <span class="v30_4">You may choose the topic for feedback by clicking the respective tile then write your feedback in the box given below.</span>
                                </div>
                                <div class="form-group required">
                                     
<div class="feedback-type">
    <div class="suggestions-box" id="sbox" onclick="getFeedbackType('S')"><div class="suggestions-text">Suggestions</div>
      <div class="suggestions-image">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
<path d="M20 2H4C3.46957 2 2.96086 2.21071 2.58579 2.58579C2.21071 2.96086 2 3.46957 2 4V22L6 18H20C20.5304 18 21.0391 17.7893 21.4142 17.4142C21.7893 17.0391 22 16.5304 22 16V4C22 3.46957 21.7893 2.96086 21.4142 2.58579C21.0391 2.21071 20.5304 2 20 2ZM8 14H6V12H8V14ZM8 11H6V9H8V11ZM8 8H6V6H8V8ZM15 14H10V12H15V14ZM18 11H10V9H18V11ZM18 8H10V6H18V8Z" fill="#A493FD" style=""></path>
</svg>

      </div>
      </div> 

      <div class="problem-box" id="pbox" onclick="getFeedbackType('I')"><div class="problem-text">I’m facing a problem</div>
      <div class="problem-image">
      <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
      <path d="M13.5 14H11.5V9H13.5V14ZM13.5 18H11.5V16H13.5V18ZM1.5 21H23.5L12.5 2L1.5 21Z" fill="#FF900D"></path>
      </svg>
      </div>
      </div>  

      <div class="lov-box" id="lbox" onclick="getFeedbackType('H')">
      <div class="lov-text">I’m loving this</div>
      <div class="lov-image">

      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
<path d="M12.75 3.94C13.75 3.22 14.91 2.86 16.22 2.86C16.94 2.86 17.73 3.05 18.59 3.45C19.45 3.84 20.13 4.3 20.63 4.83C21.66 6.11 22.09 7.6 21.94 9.3C21.78 11 21.22 12.33 20.25 13.27L12.66 20.86C12.47 21.05 12.23 21.14 11.95 21.14C11.67 21.14 11.44 21.05 11.25 20.86C11.06 20.67 10.97 20.44 10.97 20.16C10.97 19.88 11.06 19.64 11.25 19.45L15.84 14.86C16.09 14.64 16.09 14.41 15.84 14.16C15.59 13.91 15.36 13.91 15.14 14.16L10.55 18.75C10.36 18.94 10.13 19.03 9.83999 19.03C9.55999 19.03 9.32999 18.94 9.13999 18.75C8.94999 18.56 8.85999 18.33 8.85999 18.05C8.85999 17.77 8.94999 17.53 9.13999 17.34L13.73 12.75C14 12.5 14 12.25 13.73 12C13.5 11.75 13.28 11.75 13.03 12L8.43999 16.64C8.24999 16.83 7.99999 16.92 7.72999 16.92C7.44999 16.92 7.20999 16.83 6.99999 16.64C6.79999 16.45 6.69999 16.22 6.69999 15.94C6.69999 15.66 6.80999 15.41 7.02999 15.19L11.63 10.59C11.88 10.34 11.88 10.11 11.63 9.89C11.38 9.67 11.14 9.67 10.92 9.89L6.27999 14.5C6.05999 14.7 5.82999 14.81 5.57999 14.81C5.29999 14.81 5.05999 14.71 4.87999 14.5C4.68999 14.3 4.58999 14.06 4.58999 13.78C4.58999 13.5 4.68999 13.27 4.87999 13.08C7.93999 10 9.82999 8.14 10.55 7.45L14.11 10.97C14.5 11.34 14.95 11.53 15.5 11.53C16.2 11.53 16.75 11.25 17.16 10.69C17.44 10.28 17.54 9.83 17.46 9.33C17.38 8.83 17.17 8.41 16.83 8.06L12.75 3.94ZM14.81 10.27L10.55 6L3.46999 13.08C2.62999 12.23 2.14999 10.93 2.03999 9.16C1.92999 7.4 2.40999 5.87 3.46999 4.59C4.65999 3.41 6.07999 2.81 7.72999 2.81C9.38999 2.81 10.8 3.41 11.95 4.59L16.22 8.86C16.41 9.05 16.5 9.28 16.5 9.56C16.5 9.84 16.41 10.08 16.22 10.27C16.03 10.45 15.8 10.55 15.5 10.55C15.23 10.55 15 10.45 14.81 10.27Z" fill="#FF6464"></path>
</svg>
      </div>
      </div>  
    </div>
                                     
<div class="form-group" style="padding-left: 20px;padding-right: 19px;padding-top: 20px;">
<textarea maxlength="2000" type="text" id="comments" required="" class="v30_13" placeholder="Write your feedback here..."></textarea></div>

                                </div>
                                
                            </fieldset>
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                <div class="col-md-3 pull-right">
                                    

                                    <div class="v34_56" style="float:right;">
<div class="v34_53" style="cursor:pointer"> 
<a  class="v34_52" data-dismiss="modal" aria-label="Close" >Will do later</a>
</div>
<button class="v30_20" id="btnsavefeedback" onclick="saveFeedback()";><span  class="v30_21">Submit</span>
</button>
</div>

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

    
  
     <style>  
 
 
.v28_1 {
  position: relative;
width: 1440px;
height: 532px;

background: #F2F1F1;
}
.v28_2 {
 
  
  background: rgba(255,255,255,1);
  opacity: 1;
  position: absolute;
  
  
  box-shadow: 0px 8px 35px rgba(0, 0, 0, 0.1599999964237213);
  overflow: hidden;
  
height: 530px;
left: 406px;
right: 406px;
 
}
.v28_4 {
  
    font-family: Montserrat;
    font-style: normal;
    font-weight: 600;
    font-size: 24px;
    line-height: 29px;
    color: #000000;


}
.v28_3 {
    
    font-family: Montserrat;
    font-style: normal;
    font-weight: 600;
    font-size: 24px;
    line-height: 29px;
    color: #000000;

}
.v28_6 {
     
    font-family: Montserrat;
    font-style: normal;
    font-weight: 600;
    font-size: 14px;
    line-height: 17px;
    color: #494949;
}
.v28_5 {
    width: 372px;
    color: #494949;
    position: absolute;
    top: 0px;
    left: 92px;
    font-family: Montserrat;
    font-size: 14px;
    font-weight: 600;
    height: 17px;
    font-style: normal;
    line-height: 17px;
}
.v34_63 {
  
    height: 1px;
   padding:0px !important;
    background: #C4C4C4;
}
.v28_33 {
  width: 898px;
  height: 2px;
  background: rgba(196,196,196,1);
  opacity: 1;
  position: absolute;
  top: 10px;
  left: 10px;
  overflow: hidden;
}
.v29_47 {
  position: absolute;
    width: 408px;
    height: 22px;
    left: 533px;
    top: 145px;
    font-family: Montserrat;
    font-style: normal;
    font-weight: 600;
    font-size: 18px;
    line-height: 22px;
    color: #000000;
}
}
.v28_7 {
  width: 441px;
  color: rgba(0,0,0,1);
  position: absolute;
  top: 10px;
  left: 10px;
  font-family: Poppins;
  font-weight: Medium;
  font-size: 20px;
  opacity: 1;
  text-align: left;
}
 
  
.emoji-container {  
 display: flex;
    flex-direction: row;
    justify-content: space-evenly;
    /* align-items: center; */
    padding: 25px;
              
}

.emoji {
            cursor: pointer;
        }
        
.emoji:hover {
        fill: #F3D02F;
        }
        .suggestions-box:hover{
          border-color:green;
           cursor: pointer;

        }
        .problem-box:hover{
          border-color:green;
           cursor: pointer;

        }
        .lov-box:hover{
          border-color:green;
           cursor: pointer;

        }
       
.v30_4 {
    font-family: Montserrat;
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 17px;
    color: #494949;

}
.v30_3 {
  
  color: rgba(0,0,0,1);
  
  font-family: Poppins;
  font-weight: Medium;
  font-size: 18px;
  opacity: 1;
  text-align: left;
}
 
.v30_13 {
   display: flex;
flex-direction: row;
align-items: flex-start;
margin-top: 15px;
 width: -webkit-fill-available;
    height: auto;

border: 1px solid #C8C1C1;
box-sizing: border-box;
border-radius: 8px;
}
.v30_12 { 

font-family: Montserrat;
font-style: normal;
font-weight: 500;
font-size: 18px;
line-height: 22px;
/* identical to box height */


color: #686767;


/* Inside Auto Layout */

flex: none;
order: 0;
flex-grow: 0;
margin: 0px 10px;
}
.v34_56 {
 display: flex;
flex-direction: row;
 
align-items: center;
padding: 0px;
 
}
.v34_53 {
   display: flex;
flex-direction: row;
align-items: flex-start;
 
 

border-radius: 8px;

/* Inside Auto Layout */

flex: none;
order: 0;
flex-grow: 0;
margin: 0px 24px;
}
.v34_52 {
   
font-family: Montserrat;
font-style: normal;
font-weight: bold;
font-size: 14px;
line-height: 17px;
/* identical to box height */


color: rgba(0, 0, 0, 0.42);


/* Inside Auto Layout */

flex: none;
order: 0;
flex-grow: 0;
margin: 0px 0px;
}
.v30_20 {
   display: flex;
flex-direction: row;
align-items: flex-start;
padding: 5px 0px;

position: static;
width: fit-content;
 
left: 128px;
top: 0px;

background: rgba(52, 116, 240, 0.68);
box-shadow: 0px 6px 30px rgba(74, 131, 239, 0.2);
border-radius: 8px;

/* Inside Auto Layout */

flex: none;
order: 1;
flex-grow: 0;
margin: 0px 32px;
}
.v30_21 {
  position: static;
width: 68px;
height: 22px;
left: 16px;
top: 12px;

font-family: Montserrat;
font-style: normal;
font-weight: 600;
font-size: 18px;
line-height: 22px;
/* identical to box height */


color: #FFFFFF;


/* Inside Auto Layout */

flex: none;
order: 0;
flex-grow: 0;
margin: 0px 10px;
}  
 
 
 .lov-box {

  display: flex;
flex-direction: row;
justify-content: center;
align-items: center;
padding: 4px 8px;
background: #FFFFFF;
border: 1px solid #C8C1C1;
box-sizing: border-box;
border-radius: 8px;

/* Inside Auto Layout */

flex: none;
order: 2;
flex-grow: 0;
margin: 0px 5px;

 }

 .lov-text {

  position: static;
 
height: 19px;
left: 8px;
top: 6.5px;

font-family: Sen;
font-style: normal;
font-weight: bold;
font-size: 16px;
line-height: 19px;
display: flex;
align-items: center;
letter-spacing: -0.02em;

color: #2C2B2B;


/* Inside Auto Layout */

flex: none;
order: 0;
flex-grow: 0;
margin: 0px 4px;

 }
.lov-image {
 position: static;
width: 24px;
height: 24px;
left: 121px;
top: 4px;


/* Inside Auto Layout */

flex: none;
order: 1;
flex-grow: 0;
margin: 0px 4px;
}

.feedback-type {
 display: flex;
flex-direction: row;
justify-content: space-between;
align-items: center;
padding: 0px;
 
    
}
.suggestions-box{
   
display: flex;
flex-direction: row;
justify-content: center;
align-items: center;
padding: 4px 8px; 

background: #FFFFFF;
border: 1px solid #C8C1C1;
box-sizing: border-box;
border-radius: 8px;

/* Inside Auto Layout */

flex: none;
order: 0;
flex-grow: 0;
margin: 0px 5px;
}

.suggestions-text
{ 
position: static;
 
height: 24px;
left: 8px;
top: 4px;

font-family: Sen;
font-style: normal;
font-weight: bold;
font-size: 16px;
line-height: 19px;
display: flex;
align-items: center;
letter-spacing: -0.02em;

color: #2C2B2B;


/* Inside Auto Layout */

flex: none;
order: 0;
flex-grow: 0;
margin: 0px 4px;
}

.suggestions-image{
 position: static;
width: 24px;
height: 24px;
left: 105px;
top: 4px;


/* Inside Auto Layout */

flex: none;
order: 1;
flex-grow: 0;
margin: 0px 4px;
}


.problem-box{
 display: flex;
flex-direction: row;
justify-content: center;
align-items: center;
padding: 4px 8px;
 

background: #FFFFFF;
border: 1px solid #C8C1C1;
box-sizing: border-box;
border-radius: 8px;

/* Inside Auto Layout */
 
}

.problem-image
{
  position: static;
width: 24px;
height: 24px;
left: 170px;
top: 4px;


/* Inside Auto Layout */

flex: none;
order: 1;
flex-grow: 0;
margin: 0px 4px;

 
}

.problem-text{
 position: static;
width: 138px;
height: 19px;
left: 8px;
top: 6.5px;

font-family: Sen;
font-style: normal;
font-weight: bold;
font-size: 16px;
line-height: 19px;
display: flex;
align-items: center;
letter-spacing: -0.02em;

color: #2C2B2B;


/* Inside Auto Layout */

flex: none;
order: 0;
flex-grow: 0;
margin: 0px 4px;
}

</style>


   <script type="text/javascript">
        function getRating($rating_id) {
          //alert($rating_id);
           $('input[name="rating_id"]').val($rating_id);
                      

 var elements = document.getElementsByClassName("emoji"); // get all elements
	for(var i = 0; i < elements.length; i++){
		elements[i].style.fill='#C8C1C1';
	}

if($rating_id==1)
emoji1.style.fill='#F3D02F';
else if($rating_id==2)
emoji2.style.fill='#F3D02F';
else if($rating_id==3)
emoji3.style.fill='#F3D02F';else if($rating_id==4)
emoji4.style.fill='#F3D02F';else if($rating_id==5)
emoji5.style.fill='#F3D02F';

        }


         function getFeedbackType($feedback_type) {
          //alert($feedback_type);
           $('input[name="feedback_type"]').val($feedback_type);                    
 

          if($feedback_type=='S')
          {
          sbox.style.borderColor='#48E112';
          pbox.style.borderColor='#C8C1C1';
          lbox.style.borderColor='#C8C1C1';          
          }
          else if($feedback_type=='I')
          {
          pbox.style.borderColor='#48E112';
           sbox.style.borderColor='#C8C1C1';
          lbox.style.borderColor='#C8C1C1'; 
          }
          else if($feedback_type=='H')
          {
          lbox.style.borderColor='#48E112'; 
          pbox.style.borderColor='#C8C1C1';
          sbox.style.borderColor='#C8C1C1'; 
          }

        }

function saveFeedback()
{
  $('#btnsavefeedback').addClass('disabled');
  
  var feedback_type=$('input[name="feedback_type"]').val();
  var rating_id=  $('input[name="rating_id"]').val();
  var comments= document.getElementById("comments").value; 
   console.log("Save Feedback");
   console.log(feedback_type);
   console.log(rating_id);
   console.log(comments);
  
     if(rating_id=="")
   {
     alert("Please select the overall shopping experience");
     $('#btnsavefeedback').removeClass('disabled');
     return;
   }
    if(feedback_type=="")
   {
     alert("Please select the respective tile");
          $('#btnsavefeedback').removeClass('disabled');


     return;
   }
   if(comments=="")
   {
     alert("Please provide comments");
         $('#btnsavefeedback').removeClass('disabled');


     return;
   }
    $("#feedbackModal").modal('hide');
         $('#btnsavefeedback').removeClass('disabled');



    var redirectURL = '<?php echo $base; ?>';
      iziToast.success({
                                position: 'topRight',
                                        message: 'Thank you for your valuable feedback'
                                         });
 
      $.ajax({
                        url: 'index.php?path=account/feedback/saveFeedback',
                        type: 'post',
                        async: false,
                        data:{ rating_id : rating_id, feedback_type : feedback_type, comments : comments },

                        dataType: 'json',
                        cache: false,
                        success: function(json) {

                            console.log(json);
                             
                            console.log("feedback saving");
                            
                            if (json.status == 0) {
                                //$('#feedback-message').html(json['message']);
                                //$('#feedback-success-message').html('');
                               
                            } else {
                                console.log("feedback success");
                                $('#feedback-panel').html(json.html);
                                //$('#feedbackModal').modal('hide');
                                
                                //return true;
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                             return false;
                        }
                    }); 
  
        


}
        
      </script> 
