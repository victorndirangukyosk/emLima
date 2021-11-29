
    </div> <!-- /container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="ui/javascript/jquery/jquery-2.1.1.min.js"></script>
    <script src="ui/javascript/shopper.js"></script>    
    <script src="ui/javascript/bootstrap/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="ui/javascript/bootstrap/js/ie10-viewport-bug-workaround.js"></script>
    
    <?php if($this->request->get['path'] != 'shopper/order/track'){ ?>
    <script src="ui/javascript/locator.js"></script>    
    <?php } ?>
        
  </body>
</html>


