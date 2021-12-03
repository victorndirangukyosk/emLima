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
        <?php foreach($orders as $order) { ?>        
        <div class="main-content">
            <table class="products-table">
                <thead class="font-bold">
                    <tr>
                        <td>SKU</td>
                        <td>Product</td>
                        <td>Product Notes</td>
                        <td class="text-center">Quantity</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $product) { ?>
                    <tr>
                        <td><?= $product['product_id'] ?></td>
                        <td><?= $product['name'] ?></td>
                        <td><?= $product['product_note'] ?></td>
                        <td class="text-center"><?= $product['quantity'] ?> <?= $product['unit'] ?></td>
                    </tr>
                    <?php } ?>

                    <?php foreach($totals as $total) { ?>
                    <tr class="font-bold text-right">
                        <td colspan="4">
                            <?= $total['title'] ?>
                        </td>
                        <td>
                            <?= $total['text'] ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <p class="mt-4">
                <?php foreach($totals as $total) { ?>
                <?php if($total['title'] == 'Total') { ?>
                <span class="font-bold">Total In Words</span>
                <?= $total['amount_in_words']?>
                <?php } ?>
                <?php } ?>
            </p>

            <div class="row mt-4">
                <div class="payment-details">
                    <p class="text-subheader font-bold mb-2">BANK TRANSFER</p>
                    <ul class="text-left">
                        <li>Beneficiary Name: KWIKBASKET SOLUTIONS LIMITED</li>
                        <li>Account Currency: KES</li>
                        <li>Account Number: 0100006985957</li>
                        <li>Bank Name: STANBIC BANK KENYA LTD</li>
                        <li>Sort Code: 31007</li>
                        <li>Branch: Chiromo Road, Nairobi</li>
                        <li>SWIFT Code: SBICKENX</li>
                    </ul>
                </div>
                <div class="payment-details">
                    <p class="text-subheader font-bold mb-2 text-right">LIPA NA MPESA</p>
                    <ul class="text-right">
                        <li>Go to the M-PESA Menu</li>
                        <li>Select Lipa Na M-PESA</li>
                        <li>Select Pay Bill</li>
                        <li>Enter <span class="font-bold">4029127</span></li>
                        <li>Enter <span class="font-bold">KB
                                <?= $order_id ?>
                            </span> as the account number</li>
                        <li>Enter total amount</li>
                        <li>Enter M-PESA Pin</li>
                    </ul>
                </div>
            </div>
        </div>
        <?php } ?>
    </body>

</html>
