<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KwikBasket Invoice #<?= $orders[0]['order_id'] ?></title>
    <link rel="stylesheet" href="ui/stylesheet/bootstrap.min.css">
    <link rel="stylesheet" href="ui/javascript/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="ui/stylesheet/print.css">
</head>

<body>
<?php foreach($orders as $order) { ?>
    
<div class="page">
    <table class="document-container">
        <tbody>
        <tr>
            <td>
                <div class="content">
                    <div class="container">
                        <div class="row mb-4">
                            <div class="col-md-4 company-details">
                                <img width="210" src="ui/images/logo.png" alt="KwikBasket Logo" class="mb-2">
                                <div class="text-left address-block">
                                    <ul class="list-block">
                                        <li>12 Githuri Rd, Parklands, Nairobi</li>
                                        <li>+254780703586</li>
                                        <li>operations@kwikbasket.com</li>
                                        <li>www.kwikbasket.com</li>
                                        <li>KRA PIN Number P051904531E</li>
                                    </ul>
                                    <br><br>
                                       <h5 class="bold text-uppercase mb-3">TO <?= $order['customer_company_name'] ?></h5>
                                <ul class="list-block">
                                    <li><?= $order['shipping_name'] ?></li>
                                    <li><?= $order['telephone'] ?></li>
                                    <li class="mb-2"><?= $order['email'] ?></li>
                                    <li>
                                        <p class="bold"><?= $order['shipping_name_original'] ?></br> <?= $order['shipping_address'] ?></p>
                                    </li>
                                </ul>

                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <?php if($order['vendor_terms_cod'] == 1 || $order['payment_terms'] == 'Payment On Delivery') { ?>
                               <!-- <img width="210" src="ui/images/cod.png" alt="COD" class="mb-2">-->
                               <img width="210" src="ui/images/pod.png" alt="POD" class="mb-2">

                                <!--<svg xmlns="http://www.w3.org/2000/svg" width="600" height="300" viewBox="100 230 600 600" fill="none">
                                <rect width="400" height="400" fill="white"></rect>
                                <mask id="path-1-inside-1" fill="white">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M387.194 296.866C389.707 302.932 391 309.434 391 316L393 316C397.418 316 401 319.582 401 324V343H380.993C380.998 342.834 381 342.667 381 342.5C381 333.387 373.613 326 364.5 326C355.387 326 348 333.387 348 342.5C348 342.667 348.002 342.834 348.007 343H270.993C270.998 342.834 271 342.667 271 342.5C271 333.387 263.613 326 254.5 326C245.387 326 238 333.387 238 342.5C238 342.667 238.002 342.834 238.007 343H211V316L341 316V266C347.566 266 354.068 267.293 360.134 269.806C366.2 272.319 371.712 276.002 376.355 280.645C380.998 285.288 384.681 290.8 387.194 296.866Z"></path>
                                </mask>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M387.194 296.866C389.707 302.932 391 309.434 391 316L393 316C397.418 316 401 319.582 401 324V343H380.993C380.998 342.834 381 342.667 381 342.5C381 333.387 373.613 326 364.5 326C355.387 326 348 333.387 348 342.5C348 342.667 348.002 342.834 348.007 343H270.993C270.998 342.834 271 342.667 271 342.5C271 333.387 263.613 326 254.5 326C245.387 326 238 333.387 238 342.5C238 342.667 238.002 342.834 238.007 343H211V316L341 316V266C347.566 266 354.068 267.293 360.134 269.806C366.2 272.319 371.712 276.002 376.355 280.645C380.998 285.288 384.681 290.8 387.194 296.866Z" fill="white"></path>
                                <path d="M391 316H390V317L391 317L391 316ZM387.194 296.866L386.27 297.249V297.249L387.194 296.866ZM393 316V315H393L393 316ZM401 343V344H402V343H401ZM380.993 343L379.993 342.97L379.962 344H380.993V343ZM348.007 343V344H349.038L349.007 342.97L348.007 343ZM270.993 343L269.993 342.97L269.962 344H270.993V343ZM238.007 343V344H239.038L239.007 342.97L238.007 343ZM211 343H210V344H211V343ZM211 316L211 315H210V316H211ZM341 316V317H342V316H341ZM341 266V265H340V266H341ZM360.134 269.806L360.517 268.882V268.882L360.134 269.806ZM376.355 280.645L377.062 279.938V279.938L376.355 280.645ZM392 316C392 309.303 390.681 302.671 388.118 296.483L386.27 297.249C388.733 303.193 390 309.565 390 316H392ZM391 317L393 317L393 315L391 315L391 317ZM393 317C396.866 317 400 320.134 400 324H402C402 319.029 397.971 315 393 315V317ZM400 324V343H402V324H400ZM401 342H380.993V344H401V342ZM381.992 343.03C381.997 342.854 382 342.677 382 342.5H380C380 342.657 379.998 342.814 379.993 342.97L381.992 343.03ZM382 342.5C382 332.835 374.165 325 364.5 325V327C373.06 327 380 333.94 380 342.5H382ZM364.5 325C354.835 325 347 332.835 347 342.5H349C349 333.94 355.94 327 364.5 327V325ZM347 342.5C347 342.677 347.003 342.854 347.008 343.03L349.007 342.97C349.002 342.814 349 342.657 349 342.5H347ZM348.007 342H270.993V344H348.007V342ZM271.992 343.03C271.997 342.854 272 342.677 272 342.5H270C270 342.657 269.998 342.814 269.993 342.97L271.992 343.03ZM272 342.5C272 332.835 264.165 325 254.5 325V327C263.06 327 270 333.94 270 342.5H272ZM254.5 325C244.835 325 237 332.835 237 342.5H239C239 333.94 245.94 327 254.5 327V325ZM237 342.5C237 342.677 237.003 342.854 237.008 343.03L239.007 342.97C239.002 342.814 239 342.657 239 342.5H237ZM238.007 342H211V344H238.007V342ZM212 343V316H210V343H212ZM211 317L341 317V315L211 315L211 317ZM340 266V316H342V266H340ZM360.517 268.882C354.329 266.319 347.697 265 341 265V267C347.435 267 353.807 268.267 359.751 270.73L360.517 268.882ZM377.062 279.938C372.327 275.202 366.704 271.445 360.517 268.882L359.751 270.73C365.696 273.192 371.098 276.802 375.648 281.352L377.062 279.938ZM388.118 296.483C385.555 290.296 381.798 284.673 377.062 279.938L375.648 281.352C380.198 285.902 383.808 291.304 386.27 297.249L388.118 296.483Z" fill="black" mask="url(#path-1-inside-1)"></path>
                                <path d="M381 306C381 302.06 380.224 298.159 378.716 294.52C377.209 290.88 374.999 287.573 372.213 284.787C369.427 282.001 366.12 279.791 362.48 278.284C358.841 276.776 354.94 276 351 276L351 306H381Z" fill="#333333"></path>
                                <path d="M268 342.5C268 349.956 261.956 356 254.5 356C247.044 356 241 349.956 241 342.5C241 335.044 247.044 329 254.5 329C261.956 329 268 335.044 268 342.5ZM246.238 342.5C246.238 347.063 249.937 350.762 254.5 350.762C259.063 350.762 262.762 347.063 262.762 342.5C262.762 337.937 259.063 334.238 254.5 334.238C249.937 334.238 246.238 337.937 246.238 342.5Z" fill="#333333"></path>
                                <path d="M378 342.5C378 349.956 371.956 356 364.5 356C357.044 356 351 349.956 351 342.5C351 335.044 357.044 329 364.5 329C371.956 329 378 335.044 378 342.5ZM356.238 342.5C356.238 347.063 359.937 350.762 364.5 350.762C369.063 350.762 372.762 347.063 372.762 342.5C372.762 337.937 369.063 334.238 364.5 334.238C359.937 334.238 356.238 337.937 356.238 342.5Z" fill="#333333"></path>
                                <rect x="201.5" y="246.5" width="140" height="70" stroke="black"></rect>
                                <path d="M213.536 295.6C214.624 295.6 215.48 295.86 216.104 296.38C216.728 296.9 217.04 297.616 217.04 298.528C217.04 299.44 216.728 300.156 216.104 300.676C215.48 301.196 214.624 301.456 213.536 301.456H211.46V304H210.26V295.6H213.536ZM213.5 300.412C214.26 300.412 214.84 300.252 215.24 299.932C215.64 299.604 215.84 299.136 215.84 298.528C215.84 297.92 215.64 297.456 215.24 297.136C214.84 296.808 214.26 296.644 213.5 296.644H211.46V300.412H213.5ZM220.892 297.58C221.772 297.58 222.444 297.796 222.908 298.228C223.38 298.66 223.616 299.304 223.616 300.16V304H222.524V303.16C222.332 303.456 222.056 303.684 221.696 303.844C221.344 303.996 220.924 304.072 220.436 304.072C219.724 304.072 219.152 303.9 218.72 303.556C218.296 303.212 218.084 302.76 218.084 302.2C218.084 301.64 218.288 301.192 218.696 300.856C219.104 300.512 219.752 300.34 220.64 300.34H222.464V300.112C222.464 299.616 222.32 299.236 222.032 298.972C221.744 298.708 221.32 298.576 220.76 298.576C220.384 298.576 220.016 298.64 219.656 298.768C219.296 298.888 218.992 299.052 218.744 299.26L218.264 298.396C218.592 298.132 218.984 297.932 219.44 297.796C219.896 297.652 220.38 297.58 220.892 297.58ZM220.628 303.184C221.068 303.184 221.448 303.088 221.768 302.896C222.088 302.696 222.32 302.416 222.464 302.056V301.168H220.688C219.712 301.168 219.224 301.496 219.224 302.152C219.224 302.472 219.348 302.724 219.596 302.908C219.844 303.092 220.188 303.184 220.628 303.184ZM231.259 297.64L228.175 304.636C227.903 305.284 227.583 305.74 227.215 306.004C226.855 306.268 226.419 306.4 225.907 306.4C225.595 306.4 225.291 306.348 224.995 306.244C224.707 306.148 224.467 306.004 224.275 305.812L224.767 304.948C225.095 305.26 225.475 305.416 225.907 305.416C226.187 305.416 226.419 305.34 226.603 305.188C226.795 305.044 226.967 304.792 227.119 304.432L227.323 303.988L224.515 297.64H225.715L227.935 302.728L230.131 297.64H231.259ZM240.29 297.58C241.09 297.58 241.722 297.812 242.186 298.276C242.658 298.74 242.894 299.428 242.894 300.34V304H241.742V300.472C241.742 299.856 241.598 299.392 241.31 299.08C241.03 298.768 240.626 298.612 240.098 298.612C239.514 298.612 239.05 298.796 238.706 299.164C238.362 299.524 238.19 300.044 238.19 300.724V304H237.038V300.472C237.038 299.856 236.894 299.392 236.606 299.08C236.326 298.768 235.922 298.612 235.394 298.612C234.81 298.612 234.346 298.796 234.002 299.164C233.658 299.524 233.486 300.044 233.486 300.724V304H232.334V297.64H233.438V298.588C233.67 298.26 233.974 298.012 234.35 297.844C234.726 297.668 235.154 297.58 235.634 297.58C236.13 297.58 236.57 297.68 236.954 297.88C237.338 298.08 237.634 298.372 237.842 298.756C238.082 298.388 238.414 298.1 238.838 297.892C239.27 297.684 239.754 297.58 240.29 297.58ZM250.797 300.856C250.797 300.944 250.789 301.06 250.773 301.204H245.613C245.685 301.764 245.929 302.216 246.345 302.56C246.769 302.896 247.293 303.064 247.917 303.064C248.677 303.064 249.289 302.808 249.753 302.296L250.389 303.04C250.101 303.376 249.741 303.632 249.309 303.808C248.885 303.984 248.409 304.072 247.881 304.072C247.209 304.072 246.613 303.936 246.093 303.664C245.573 303.384 245.169 302.996 244.881 302.5C244.601 302.004 244.461 301.444 244.461 300.82C244.461 300.204 244.597 299.648 244.869 299.152C245.149 298.656 245.529 298.272 246.009 298C246.497 297.72 247.045 297.58 247.653 297.58C248.261 297.58 248.801 297.72 249.273 298C249.753 298.272 250.125 298.656 250.389 299.152C250.661 299.648 250.797 300.216 250.797 300.856ZM247.653 298.552C247.101 298.552 246.637 298.72 246.261 299.056C245.893 299.392 245.677 299.832 245.613 300.376H249.693C249.629 299.84 249.409 299.404 249.033 299.068C248.665 298.724 248.205 298.552 247.653 298.552ZM255.793 297.58C256.601 297.58 257.241 297.816 257.713 298.288C258.193 298.752 258.433 299.436 258.433 300.34V304H257.281V300.472C257.281 299.856 257.133 299.392 256.837 299.08C256.541 298.768 256.117 298.612 255.565 298.612C254.941 298.612 254.449 298.796 254.089 299.164C253.729 299.524 253.549 300.044 253.549 300.724V304H252.397V297.64H253.501V298.6C253.733 298.272 254.045 298.02 254.437 297.844C254.837 297.668 255.289 297.58 255.793 297.58ZM264.141 303.628C263.973 303.772 263.765 303.884 263.517 303.964C263.269 304.036 263.013 304.072 262.749 304.072C262.109 304.072 261.613 303.9 261.261 303.556C260.909 303.212 260.733 302.72 260.733 302.08V298.588H259.653V297.64H260.733V296.248H261.885V297.64H263.709V298.588H261.885V302.032C261.885 302.376 261.969 302.64 262.137 302.824C262.313 303.008 262.561 303.1 262.881 303.1C263.233 303.1 263.533 303 263.781 302.8L264.141 303.628ZM271.48 304.072C270.848 304.072 270.28 303.932 269.776 303.652C269.272 303.372 268.876 302.988 268.588 302.5C268.308 302.004 268.168 301.444 268.168 300.82C268.168 300.196 268.308 299.64 268.588 299.152C268.876 298.656 269.272 298.272 269.776 298C270.28 297.72 270.848 297.58 271.48 297.58C272.112 297.58 272.676 297.72 273.172 298C273.676 298.272 274.068 298.656 274.348 299.152C274.636 299.64 274.78 300.196 274.78 300.82C274.78 301.444 274.636 302.004 274.348 302.5C274.068 302.988 273.676 303.372 273.172 303.652C272.676 303.932 272.112 304.072 271.48 304.072ZM271.48 303.064C271.888 303.064 272.252 302.972 272.572 302.788C272.9 302.596 273.156 302.332 273.34 301.996C273.524 301.652 273.616 301.26 273.616 300.82C273.616 300.38 273.524 299.992 273.34 299.656C273.156 299.312 272.9 299.048 272.572 298.864C272.252 298.68 271.888 298.588 271.48 298.588C271.072 298.588 270.704 298.68 270.376 298.864C270.056 299.048 269.8 299.312 269.608 299.656C269.424 299.992 269.332 300.38 269.332 300.82C269.332 301.26 269.424 301.652 269.608 301.996C269.8 302.332 270.056 302.596 270.376 302.788C270.704 302.972 271.072 303.064 271.48 303.064ZM279.769 297.58C280.577 297.58 281.217 297.816 281.689 298.288C282.169 298.752 282.409 299.436 282.409 300.34V304H281.257V300.472C281.257 299.856 281.109 299.392 280.813 299.08C280.517 298.768 280.093 298.612 279.541 298.612C278.917 298.612 278.425 298.796 278.065 299.164C277.705 299.524 277.525 300.044 277.525 300.724V304H276.373V297.64H277.477V298.6C277.709 298.272 278.021 298.02 278.413 297.844C278.813 297.668 279.265 297.58 279.769 297.58ZM293.764 295.096V304H292.66V302.992C292.404 303.344 292.08 303.612 291.688 303.796C291.296 303.98 290.864 304.072 290.392 304.072C289.776 304.072 289.224 303.936 288.736 303.664C288.248 303.392 287.864 303.012 287.584 302.524C287.312 302.028 287.176 301.46 287.176 300.82C287.176 300.18 287.312 299.616 287.584 299.128C287.864 298.64 288.248 298.26 288.736 297.988C289.224 297.716 289.776 297.58 290.392 297.58C290.848 297.58 291.268 297.668 291.652 297.844C292.036 298.012 292.356 298.264 292.612 298.6V295.096H293.764ZM290.488 303.064C290.888 303.064 291.252 302.972 291.58 302.788C291.908 302.596 292.164 302.332 292.348 301.996C292.532 301.652 292.624 301.26 292.624 300.82C292.624 300.38 292.532 299.992 292.348 299.656C292.164 299.312 291.908 299.048 291.58 298.864C291.252 298.68 290.888 298.588 290.488 298.588C290.08 298.588 289.712 298.68 289.384 298.864C289.064 299.048 288.808 299.312 288.616 299.656C288.432 299.992 288.34 300.38 288.34 300.82C288.34 301.26 288.432 301.652 288.616 301.996C288.808 302.332 289.064 302.596 289.384 302.788C289.712 302.972 290.08 303.064 290.488 303.064ZM301.692 300.856C301.692 300.944 301.684 301.06 301.668 301.204H296.508C296.58 301.764 296.824 302.216 297.24 302.56C297.664 302.896 298.188 303.064 298.812 303.064C299.572 303.064 300.184 302.808 300.648 302.296L301.284 303.04C300.996 303.376 300.636 303.632 300.204 303.808C299.78 303.984 299.304 304.072 298.776 304.072C298.104 304.072 297.508 303.936 296.988 303.664C296.468 303.384 296.064 302.996 295.776 302.5C295.496 302.004 295.356 301.444 295.356 300.82C295.356 300.204 295.492 299.648 295.764 299.152C296.044 298.656 296.424 298.272 296.904 298C297.392 297.72 297.94 297.58 298.548 297.58C299.156 297.58 299.696 297.72 300.168 298C300.648 298.272 301.02 298.656 301.284 299.152C301.556 299.648 301.692 300.216 301.692 300.856ZM298.548 298.552C297.996 298.552 297.532 298.72 297.156 299.056C296.788 299.392 296.572 299.832 296.508 300.376H300.588C300.524 299.84 300.304 299.404 299.928 299.068C299.56 298.724 299.1 298.552 298.548 298.552ZM303.291 295.096H304.443V304H303.291V295.096ZM306.643 297.64H307.795V304H306.643V297.64ZM307.219 296.416C306.995 296.416 306.807 296.344 306.655 296.2C306.511 296.056 306.439 295.88 306.439 295.672C306.439 295.464 306.511 295.288 306.655 295.144C306.807 294.992 306.995 294.916 307.219 294.916C307.443 294.916 307.627 294.988 307.771 295.132C307.923 295.268 307.999 295.44 307.999 295.648C307.999 295.864 307.923 296.048 307.771 296.2C307.627 296.344 307.443 296.416 307.219 296.416ZM315.622 297.64L312.838 304H311.662L308.878 297.64H310.078L312.262 302.74L314.494 297.64H315.622ZM322.27 300.856C322.27 300.944 322.262 301.06 322.246 301.204H317.086C317.158 301.764 317.402 302.216 317.818 302.56C318.242 302.896 318.766 303.064 319.39 303.064C320.15 303.064 320.762 302.808 321.226 302.296L321.862 303.04C321.574 303.376 321.214 303.632 320.782 303.808C320.358 303.984 319.882 304.072 319.354 304.072C318.682 304.072 318.086 303.936 317.566 303.664C317.046 303.384 316.642 302.996 316.354 302.5C316.074 302.004 315.934 301.444 315.934 300.82C315.934 300.204 316.07 299.648 316.342 299.152C316.622 298.656 317.002 298.272 317.482 298C317.97 297.72 318.518 297.58 319.126 297.58C319.734 297.58 320.274 297.72 320.746 298C321.226 298.272 321.598 298.656 321.862 299.152C322.134 299.648 322.27 300.216 322.27 300.856ZM319.126 298.552C318.574 298.552 318.11 298.72 317.734 299.056C317.366 299.392 317.15 299.832 317.086 300.376H321.166C321.102 299.84 320.882 299.404 320.506 299.068C320.138 298.724 319.678 298.552 319.126 298.552ZM324.973 298.708C325.173 298.34 325.469 298.06 325.861 297.868C326.253 297.676 326.729 297.58 327.289 297.58V298.696C327.225 298.688 327.137 298.684 327.025 298.684C326.401 298.684 325.909 298.872 325.549 299.248C325.197 299.616 325.021 300.144 325.021 300.832V304H323.869V297.64H324.973V298.708ZM334.595 297.64L331.511 304.636C331.239 305.284 330.919 305.74 330.551 306.004C330.191 306.268 329.755 306.4 329.243 306.4C328.931 306.4 328.627 306.348 328.331 306.244C328.043 306.148 327.803 306.004 327.611 305.812L328.103 304.948C328.431 305.26 328.811 305.416 329.243 305.416C329.523 305.416 329.755 305.34 329.939 305.188C330.131 305.044 330.303 304.792 330.455 304.432L330.659 303.988L327.851 297.64H329.051L331.271 302.728L333.467 297.64H334.595Z" fill="#4F4F4F"></path>
                                <path d="M241.752 255.8C243.936 255.8 245.832 256.16 247.44 256.88C249.072 257.6 250.32 258.632 251.184 259.976C252.048 261.32 252.48 262.916 252.48 264.764C252.48 266.588 252.048 268.184 251.184 269.552C250.32 270.896 249.072 271.928 247.44 272.648C245.832 273.368 243.936 273.728 241.752 273.728H236.064V281H231.384V255.8H241.752ZM241.536 269.768C243.576 269.768 245.124 269.336 246.18 268.472C247.236 267.608 247.764 266.372 247.764 264.764C247.764 263.156 247.236 261.92 246.18 261.056C245.124 260.192 243.576 259.76 241.536 259.76H236.064V269.768H241.536ZM269.277 281.36C266.709 281.36 264.393 280.808 262.329 279.704C260.265 278.576 258.645 277.028 257.469 275.06C256.293 273.068 255.705 270.848 255.705 268.4C255.705 265.952 256.293 263.744 257.469 261.776C258.645 259.784 260.265 258.236 262.329 257.132C264.393 256.004 266.709 255.44 269.277 255.44C271.845 255.44 274.161 256.004 276.225 257.132C278.289 258.236 279.909 259.772 281.085 261.74C282.261 263.708 282.849 265.928 282.849 268.4C282.849 270.872 282.261 273.092 281.085 275.06C279.909 277.028 278.289 278.576 276.225 279.704C274.161 280.808 271.845 281.36 269.277 281.36ZM269.277 277.256C270.957 277.256 272.469 276.884 273.813 276.14C275.157 275.372 276.213 274.316 276.981 272.972C277.749 271.604 278.133 270.08 278.133 268.4C278.133 266.72 277.749 265.208 276.981 263.864C276.213 262.496 275.157 261.44 273.813 260.696C272.469 259.928 270.957 259.544 269.277 259.544C267.597 259.544 266.085 259.928 264.741 260.696C263.397 261.44 262.341 262.496 261.573 263.864C260.805 265.208 260.421 266.72 260.421 268.4C260.421 270.08 260.805 271.604 261.573 272.972C262.341 274.316 263.397 275.372 264.741 276.14C266.085 276.884 267.597 277.256 269.277 277.256ZM287.81 255.8H298.826C301.514 255.8 303.902 256.328 305.99 257.384C308.078 258.416 309.698 259.892 310.85 261.812C312.002 263.708 312.578 265.904 312.578 268.4C312.578 270.896 312.002 273.104 310.85 275.024C309.698 276.92 308.078 278.396 305.99 279.452C303.902 280.484 301.514 281 298.826 281H287.81V255.8ZM298.61 277.04C300.458 277.04 302.078 276.692 303.47 275.996C304.886 275.276 305.966 274.268 306.71 272.972C307.478 271.652 307.862 270.128 307.862 268.4C307.862 266.672 307.478 265.16 306.71 263.864C305.966 262.544 304.886 261.536 303.47 260.84C302.078 260.12 300.458 259.76 298.61 259.76H292.49V277.04H298.61Z" fill="#F2994A"></path>
                                </svg>-->

                                <?php } ?>
                            </div>
                            <div class="col-md-4 text-right">
                                <h5 class="bold">TAX INVOICE # <?= $order['order_id'] ?><?= $order['invoice_no'] ?></h5>
                                <?php if($order['po_number']) { ?>
                                    <h5 class="bold">P.O. NUMBER <?= $order['po_number'] ?></h5>
                                <?php } ?>
                                <h5><?= $order['delivery_date'] ?></h5>

                                  <br>
                                 <h6 class="bold mb-3">ORDER INFO</h6>
                                <ul class="list-block">
                                    <li>Order # <?= $order['order_id'] ?></li>
                                    <li>Placed On <?= $order['date_added'] ?></li>
                                    <li>Delivered On <?= $order['delivery_date'] ?></li>
                                    <li><?= $order['shipping_method'] ?></li>
                                </ul>
                                <br>
                                <!--<?php if($order['driver_name'] != NULL) { ?>
                                <h6 class="bold mb-3">DRIVER DETAILS</h6>
                                <ul class="list-block">
                                    <li>Name : <?= $order['driver_name'] ?></li>
                                    <li>Phone : <?= $order['driver_phone'] ?></li>
                                </ul>
                                <br>
                                <?php } ?>-->

                                <?php if($order['delivery_executive_name'] != NULL) { ?>
 
                                <h6 class="bold mb-3">DELIVERY EXECUTIVE DETAILS</h6>
                                <ul class="list-block">
                                    <li>Name : <?= $order['delivery_executive_name'] ?></li>
                                    <li>Phone : <?= $order['delivery_executive_phone'] ?></li>
                                <!--<?php if($order['delivery_charge'] != NULL && $order['delivery_charge'] >0) { ?>
                                    <li>Delivery Charge : <?= $order['delivery_charge'] ?></li>
                                 <?php } ?>-->
                                </ul>
                                <br>
                                <?php } ?>
                                
                                <?php if($order['customer_experience_first_last_name'] != NULL) { ?>
                                 
                                <h6 class="bold mb-3">CUSTOMER ACCOUNT MANAGER DETAILS</h6>
                                <ul class="list-block">
                                    <li>Name : <?= $order['customer_experience_first_last_name'] ?></li>
                                    <li>Phone : <?= $order['customer_experince_phone'] ?></li>
                                <!--<?php if($order['delivery_charge'] != NULL && $order['delivery_charge'] >0) { ?>
                                    <li>Delivery Charge : <?= $order['delivery_charge'] ?></li>
                                 <?php } ?>-->
                                </ul>
                                <br>
                                <?php } ?>

                                
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                             

                            </div>

                            <div class="col-md-4 offset-md-4 text-right">
                               
                            </div>
                        </div>

                        <table class="datatable">
                            <thead class="datatable-header">
                            <tr>
                                <td>SKU</td>
                                <td>Product</td>
                                <td class="text-center">Quantity</td>
                                <td class="text-right">Unit Price</td>
                                <td class="text-right">Total</td>
                            </tr>
                            </thead>
                            <tbody class="datatable-content">
                            <?php foreach($order['products'] as $product) { ?>
                            <tr>
                                <td><?= $product['product_id'] ?></td>
                                <td><?= $product['name'] ?></td>
                                <td class="text-center"><?= $product['quantity'] ?> <?= $product['unit'] ?></td>
                                <td class="text-right"><?= $product['price'] ?></td>
                                <td class="text-right"><?= $product['total'] ?></td>
                            </tr>
                            <?php } ?>

                            <?php foreach($order['totals'] as $total) { ?>
                            <tr>
                             <?php if($total['title'] == 'VAT on Standard Delivery') { ?>
                                <td colspan="4" class="text-right" >
                                <span class="bold text-right"><?= $total['title'] ?></span>                                
                                <span style="font-weight:2px"> (VAT16)</span></td>
                                <?php } else { ?>
                                <td colspan="4" class="bold text-right" ><?= $total['title'] ?></td> 
                                <?php }   ?>
                                <td class="bold text-right"><?= $total['text'] ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        
                        <div class="mt-4">
                            <?php foreach($order['totals'] as $total) { ?>
                                <?php if($total['title'] == 'Total') { ?>
                                    <h5><strong>Total In Words </strong> <?= $total['amount_in_words']?></h5>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        
                        <table class="payment-details-table mt-4">
                            <thead>
                            <tr>
                                <td colspan="2" class="text-left">
                                    PAYMENT DETAILS
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <h6 class="bold">BANK TRANSFER</h6>
                                    <ul class="list-block">
                                        <li>Beneficiary Name: KWIKBASKET SOLUTIONS LIMITED</li>
                                        <li>Account Currency: KES</li>
                                        <li>Account Number: 0100006985957</li>
                                        <li>Bank Name: STANBIC BANK KENYA LTD</li>
                                        <li>Sort Code: 31007</li>
                                        <li>Branch: Chiromo Road, Nairobi</li>
                                        <li>SWIFT Code: SBICKENX</li>
                                    </ul>
                                </td>
                                <td class="text-right">
                                    <br>
                                    <h6 class="bold">LIPA NA MPESA</h6>
                                    <ul class="list-block">
                                        <li>Go to the M-PESA Menu</li>
                                        <li>Select Lipa Na M-PESA</li>
                                        <li>Select Pay Bill</li>
                                        <li>Enter <strong>4029127</strong></li>
                                        <li>Enter <strong>KB<?= $order['order_id'] ?></strong> as the account number</li>
                                        <li>Enter total amount</li>
                                        <li>Enter M-PESA Pin</li>
                                    </ul>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
        <tfoot class="page-footer">
        <tr>
            <td>
                <div class="footer-space">&nbsp;</div>
            </td>
        </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-left">
                    <ul class="list-block">
                        <li>KWIKBASKET SOLUTIONS LIMITED</li>
                        <li>3rd Floor, Heritan House, Woodlands Road</li>
                        <li>Nairobi, Kenya</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<div class="document-actions">
    <button class="btn btn-primary mb-2" onclick="printDocument()"><i class="fa fa-print"></i> Print Document</button>
</div>

<script>
    function printDocument()
    {
        window.print();
    }
</script>
</body>
</html>