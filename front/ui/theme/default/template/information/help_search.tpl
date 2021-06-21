<?php echo $header; ?>

    <div class="container">

        <div class="row">
            <div id="help-list" class="col-md-10 col-md-offset-1">
                <div class="heading">
                    <div class="home">
                        <a href="<?= $help ?>"><?= $text_help_center ?></a>
                    </div>
                    <i class="fa fa-chevron-right"></i>
                    <div class="title">
                        <?= $text_search ?> '<?= $q ?>' 
                    </div>
                </div>
                <ul class="list-group">
                    <?php foreach($result as $data){ ?>
                    <li class="list-group-item">
                        <div id="question-204426950" class="question">
                            <i class="fa fa-plus"></i>
                            <?= $data['question'] ?>
                        </div>
                        <div class="answer hide">
                            <p>
                                <?= $data['answer'] ?>
                            </p>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <div class="row">
            <hr>
            <div class="footer text-center" style="background: none;;padding-bottom:15px;">
                <p>
                    <?= $label_text ?>
                    <a href="#" data-toggle="modal" data-target="#contactusModal"><?= $text_submit  ?></a>
                </p>
            </div>
        </div>
    </div>
</div>
<?= $contactus_modal ?>
    <script type="text/javascript">
        $(document).delegate('#contactus', 'click', function() {
                console.log("contactus click");
                $.ajax({
                    url: 'index.php?path=information/contact',
                    type: 'post',
                    data: $('#contactus-form').serialize(),
                    dataType: 'json',
                    success: function(json) {
                        console.log(json);
                        if (json['status']) {
                            $('#contactus-message').html('');
                            $('#contactus-success-message').html(json['text_message']);
                            setTimeout(function(){ window.location.reload(false); }, 1000);
                            
                        } else {
                            $error = '';

                            if(json['error_email']){
                                $error += json['error_email']+'<br/>';
                            }
                            if(json['error_enquiry']){
                                $error += json['error_enquiry']+'<br/>';
                            }
                            if(json['error_name']){
                                $error += json['error_lastname']+'<br/>';
                            }
                            $('#contactus-message').html($error);
                        }
                    }
                });
            });
    </script>

<script>
    $(function(){
        $('.question').click(function(){
            $(this).toggleClass('active');
            $(this).parent().find('.answer').toggleClass('hide');
        });
    });
</script>
