<?php echo $header; ?>

    <div class="container">
        
        <?php foreach($categories as $row){ ?>
        <div class="row sections">
            <?php foreach($row as $category){ ?>
            <div class="col-md-4">
                <a href="<?= $this->url->link('information/help', 'category_id='.$category['category_id']) ?>" class="section">
                    <i class="<?= $category['icon'] ?>"></i>
                    <?= $category['name'] ?>
                </a>
            </div>
            <?php } ?>
        </div>
        <?php } ?>

        <div class="row">
            <hr>
            <div class="footer text-center" style="background: none;;padding-bottom:15px;">
                <p>
                    <?= $label_text ?>
                    <!--<a href="#" data-toggle="modal" data-target="#contactusModal"><?= $text_submit  ?></a>-->
                    <a href="#" data-toggle="modal" onclick="sendSelectedOrderID();" data-target="#reportissueModal"><?= $text_submit  ?></a>
                </p>
            </div>
        </div>
    </div>
    <?= $reportissue_modal ?>
    </body>
</html>


<script>



 function sendSelectedOrderID() {
                 
             $("#reportissue-success-message").html('');
              $("#reportissue-message").html('');
               $("#input-issuesummary").val();

                 
            }
            </script>