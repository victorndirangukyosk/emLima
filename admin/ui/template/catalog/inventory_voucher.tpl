<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://kwikbasket-assets.s3.ap-south-1.amazonaws.com/fonts/sofiapro/index.css">
    <!-- CSS Reset -->
    <style>
        html,
        body,
        div,
        span,
        applet,
        object,
        iframe,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        blockquote,
        pre,
        a,
        abbr,
        acronym,
        address,
        big,
        cite,
        code,
        del,
        dfn,
        em,
        img,
        ins,
        kbd,
        q,
        s,
        samp,
        small,
        strike,
        strong,
        sub,
        sup,
        tt,
        var,
        b,
        u,
        i,
        center,
        dl,
        dt,
        dd,
        ol,
        ul,
        li,
        fieldset,
        form,
        label,
        legend,
        table,
        caption,
        tbody,
        tfoot,
        thead,
        tr,
        th,
        td,
        article,
        aside,
        canvas,
        details,
        embed,
        figure,
        figcaption,
        footer,
        header,
        hgroup,
        menu,
        nav,
        output,
        ruby,
        section,
        summary,
        time,
        mark,
        audio,
        video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
        }

        /* HTML5 display-role reset for older browsers */
        article,
        aside,
        details,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        menu,
        nav,
        section {
            display: block;
        }

        body {
            line-height: 1;
        }

        ol,
        ul {
            list-style: none;
        }

        blockquote,
        q {
            quotes: none;
        }

        blockquote:before,
        blockquote:after,
        q:before,
        q:after {
            content: '';
            content: none;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
    </style>

    <!-- Utilities -->
    <style>
        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-header {
            font-size: 1.25rem;
        }

        .text-subheader {
            font-size: 1.05rem;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-bold {
            font-weight: 700;
        }

        .m-0 {
            margin: 0 !important;
        }

        .mt-0 {
            margin-top: 0 !important;
        }

        .mr-0 {
            margin-right: 0 !important;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .ml-0 {
            margin-left: 0 !important;
        }

        .mx-0 {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        .my-0 {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        .m-1 {
            margin: 0.25rem !important;
        }

        .mt-1 {
            margin-top: 0.25rem !important;
        }

        .mr-1 {
            margin-right: 0.25rem !important;
        }

        .mb-1 {
            margin-bottom: 0.25rem !important;
        }

        .ml-1 {
            margin-left: 0.25rem !important;
        }

        .mx-1 {
            margin-right: 0.25rem !important;
            margin-left: 0.25rem !important;
        }

        .my-1 {
            margin-top: 0.25rem !important;
            margin-bottom: 0.25rem !important;
        }

        .m-2 {
            margin: 0.5rem !important;
        }

        .mt-2 {
            margin-top: 0.5rem !important;
        }

        .mr-2 {
            margin-right: 0.5rem !important;
        }

        .mb-2 {
            margin-bottom: 0.5rem !important;
        }

        .ml-2 {
            margin-left: 0.5rem !important;
        }

        .mx-2 {
            margin-right: 0.5rem !important;
            margin-left: 0.5rem !important;
        }

        .my-2 {
            margin-top: 0.5rem !important;
            margin-bottom: 0.5rem !important;
        }

        .m-3 {
            margin: 1rem !important;
        }

        .mt-3 {
            margin-top: 1rem !important;
        }

        .mr-3 {
            margin-right: 1rem !important;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .ml-3 {
            margin-left: 1rem !important;
        }

        .mx-3 {
            margin-right: 1rem !important;
            margin-left: 1rem !important;
        }

        .my-3 {
            margin-top: 1rem !important;
            margin-bottom: 1rem !important;
        }

        .m-4 {
            margin: 1.5rem !important;
        }

        .mt-4 {
            margin-top: 1.5rem !important;
        }

        .mr-4 {
            margin-right: 1.5rem !important;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        .ml-4 {
            margin-left: 1.5rem !important;
        }

        .mx-4 {
            margin-right: 1.5rem !important;
            margin-left: 1.5rem !important;
        }

        .my-4 {
            margin-top: 1.5rem !important;
            margin-bottom: 1.5rem !important;
        }

        .m-5 {
            margin: 3rem !important;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .mr-5 {
            margin-right: 3rem !important;
        }

        .mb-5 {
            margin-bottom: 3rem !important;
        }

        .ml-5 {
            margin-left: 3rem !important;
        }

        .mx-5 {
            margin-right: 3rem !important;
            margin-left: 3rem !important;
        }

        .my-5 {
            margin-top: 3rem !important;
            margin-bottom: 3rem !important;
        }

        .m-auto {
            margin: auto !important;
        }

        .mt-auto {
            margin-top: auto !important;
        }

        .mr-auto {
            margin-right: auto !important;
        }

        .mb-auto {
            margin-bottom: auto !important;
        }

        .ml-auto {
            margin-left: auto !important;
        }

        .mx-auto {
            margin-right: auto !important;
            margin-left: auto !important;
        }

        .my-auto {
            margin-top: auto !important;
            margin-bottom: auto !important;
        }

        .p-0 {
            padding: 0 !important;
        }

        .pt-0 {
            padding-top: 0 !important;
        }

        .pr-0 {
            padding-right: 0 !important;
        }

        .pb-0 {
            padding-bottom: 0 !important;
        }

        .pl-0 {
            padding-left: 0 !important;
        }

        .px-0 {
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        .py-0 {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .p-1 {
            padding: 0.25rem !important;
        }

        .pt-1 {
            padding-top: 0.25rem !important;
        }

        .pr-1 {
            padding-right: 0.25rem !important;
        }

        .pb-1 {
            padding-bottom: 0.25rem !important;
        }

        .pl-1 {
            padding-left: 0.25rem !important;
        }

        .px-1 {
            padding-right: 0.25rem !important;
            padding-left: 0.25rem !important;
        }

        .py-1 {
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
        }

        .p-2 {
            padding: 0.5rem !important;
        }

        .pt-2 {
            padding-top: 0.5rem !important;
        }

        .pr-2 {
            padding-right: 0.5rem !important;
        }

        .pb-2 {
            padding-bottom: 0.5rem !important;
        }

        .pl-2 {
            padding-left: 0.5rem !important;
        }

        .px-2 {
            padding-right: 0.5rem !important;
            padding-left: 0.5rem !important;
        }

        .py-2 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .p-3 {
            padding: 1rem !important;
        }

        .pt-3 {
            padding-top: 1rem !important;
        }

        .pr-3 {
            padding-right: 1rem !important;
        }

        .pb-3 {
            padding-bottom: 1rem !important;
        }

        .pl-3 {
            padding-left: 1rem !important;
        }

        .px-3 {
            padding-right: 1rem !important;
            padding-left: 1rem !important;
        }

        .py-3 {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }

        .p-4 {
            padding: 1.5rem !important;
        }

        .pt-4 {
            padding-top: 1.5rem !important;
        }

        .pr-4 {
            padding-right: 1.5rem !important;
        }

        .pb-4 {
            padding-bottom: 1.5rem !important;
        }

        .pl-4 {
            padding-left: 1.5rem !important;
        }

        .px-4 {
            padding-right: 1.5rem !important;
            padding-left: 1.5rem !important;
        }

        .py-4 {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }

        .p-5 {
            padding: 3rem !important;
        }

        .pt-5 {
            padding-top: 3rem !important;
        }

        .pr-5 {
            padding-right: 3rem !important;
        }

        .pb-5 {
            padding-bottom: 3rem !important;
        }

        .pl-5 {
            padding-left: 3rem !important;
        }

        .px-5 {
            padding-right: 3rem !important;
            padding-left: 3rem !important;
        }

        .py-5 {
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
        }
    </style>

    <!-- Invoice Styling -->
    <style>
        body {
            font-family: "Sofia Pro", sans-serif;
            font-size: 16px;
            padding: 24px;
        }

        ul {
            line-height: 1.45rem;
        }

        .row {
            display: -webkit-box;
            /* wkhtmltopdf uses this one */
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: justify;
            justify-content: space-between;
            -webkit-justify-content: space-between;
            -ms-flex-pack: justify;
            margin-bottom: 1.5rem;
        }

        .products-table {
            width: 100%;
        }

        .products-table td {
            padding: 10px;
        }

        .products-table tr {
            border-bottom: 1px solid #9c9c9c;
        }

        @media print {
            .row {
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="row">
            <div class="company-info">
                <img width="210" class="mb-2"
                    src="data:image/png;base64, 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">
                <ul class="text-left">
                    <li>12 Githuri Rd, Parklands, Nairobi</li>
                    <li>+254101444544</li>
                    <li>operations@kwikbasket.com</li>
                    <li>www.kwikbasket.com</li>
                    <li>KRA PIN Number P051904531E</li>
                </ul>
            </div>

            <div class="invoice-info text-right">
                <p class="text-header font-bold mb-1">INVENTORY VOUCHER
                </p>
                <p class="text-header">
                    <?= date('d/m/Y') ?>
                </p>
            </div>
        </div>

       <div class="row">
            <div class="customer-info">
                <p class="mb-3 text-header font-bold">TO
                    <?= $source ?>
                </p>
            </div>

            <div class="order-info text-right">
                <p class="mb-3 font-bold text-subheader">INFO</p>
                <ul>
                    <li>Inventory #
                        <?= $product_history_id ?>
                    </li>
                    <li>Delivered On
                        <?= date($this->language->get('date_format_short'), strtotime($date_added)) ?>
                    </li>
                </ul>
            </div>
        </div> 
    </div>
    
</body>

</html>
