<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
 <div class="page-header">
    <div class="container-fluid">
      <h1><i class="fa fa-file-code-o"></i>&nbsp;<?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
        
      </ul>
    </div>
 </div>
 <div class="container-fluid">
 	<?php if ($error_warning) { ?>
        <div class="alert alert-danger autoSlideUp"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
         <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="alert alert-success autoSlideUp"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <script>$('.autoSlideUp').delay(3000).fadeOut(600, function(){ $(this).show().css({'visibility':'hidden'}); }).slideUp(600);</script>
    <?php } ?>
    <a class="BigScreen" id="bigscreen" onClick="bigscreen();"><i class="fa fa-arrows-alt"></i></a>
    <a class="BackToNormalScreen" id="backtonormalscreen" onClick="normalscreen();"><i class="fa fa-expand"></i></a>
    <a class="NormalScreen" id="normalscreen" onClick="normalscreen();"><i class="fa fa-expand"></i></a>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i>&nbsp;<span style="vertical-align:middle;font-weight:bold;">Code Editor</span></h3>
            <div class="storeSwitcherWidget">
            	<div class="form-group">
                	<?php if ($buttons) { ?>
                	<button type="submit" id="showModal" class="btn btn-info btn-sm save-changes" data-toggle="modal" data-target="#myModal"><i class="fa fa-key"></i>&nbsp;&nbsp;<?php echo $save_changes?></button>
                    <button type="submit" id="showUsers" class="btn btn-default btn-sm"><i class="fa fa-eye"></i>&nbsp;&nbsp;View all users with access</button>
                    <?php } ?>
            	</div>
            </div>
        </div>
        <div class="panel-body" style="padding: 0px;">
            <?php
            if (!function_exists('modification_vqmod')) {
                function modification_vqmod($file) {
                    if (class_exists('VQMod')) {
                        return modification(modification($file), $file);
                    } else {
                        return modification($file);
                    }
                }
            }
            ?>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"> 
                <input type="hidden" name="store_id" value="<?php echo $store['store_id']; ?>" />
				<?php require_once modification_vqmod((DIR_APPLICATION.'ui/template/module/'.$moduleNameSmall.'/tab_editor.php')); ?>
            </form>
        </div> 
    </div>
 </div>
</div>

<script>
function exitOfFullScreen(el) {
	var requestMethod = el.cancelFullScreen||el.webkitCancelFullScreen||el.mozCancelFullScreen||el.exitFullscreen;
	if (requestMethod) { // cancel full screen.
		requestMethod.call(el);
	} else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
		var wscript = new ActiveXObject("WScript.Shell");
		if (wscript !== null) {
			wscript.SendKeys("{F11}");
		}
	}
}
function requestFullScreen(el) {
	// Supports most browsers and their versions.
	var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullScreen;
	if (requestMethod) { // Native full screen.
		requestMethod.call(el);
	} else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
		var wscript = new ActiveXObject("WScript.Shell");
		if (wscript !== null) {
			wscript.SendKeys("{F11}");
		}
	}
	return false
}
function toggleFull() {
	var elem = document.body; // Make the body go full screen.
	var isInFullScreen = (document.fullScreenElement && document.fullScreenElement !== null) ||  (document.mozFullScreen || document.webkitIsFullScreen);

	if (isInFullScreen) {
		$('#iFrame').addClass("FullScreen");
		requestFullScreen(document.body);
	} else {
		//$('#backtonormalscreen').hide();
		//$('#bigscreen').hide();
		exitOfFullScreen(document);
	}
	return false;
}
function fullscreen() {
	$('#iFrame').addClass("FullScreen");
	$('#bigscreen').show();
	$('#backtonormalscreen').show();
}
function normalscreen() {
	if ($('#iFrame').hasClass("BigFullScreen")) {
		exitOfFullScreen(document);
		$('#bigscreen').show();
		$('#backtonormalscreen').show();
		$('#iFrame').addClass("FullScreen");
		$('#iFrame').removeClass("BigFullScreen");
	} else {
		$('#normalscreen').hide();
		$('#backtonormalscreen').hide();
		$('#bigscreen').hide();
		$('#iFrame').removeClass("FullScreen");
	}
}
function bigscreen() {
	if ($('#iFrame').hasClass("BigFullScreen")) {
		exitOfFullScreen(document);
		$('#iFrame').removeClass("BigFullScreen");
		$('#iFrame').removeClass("FullScreen");
		$('#backtonormalscreen').hide();
		$('#bigscreen').hide();
	} else {
		requestFullScreen(document.body);
		$('#bigscreen').show();
		$('#backtonormalscreen').show();
		$('#iFrame').addClass("BigFullScreen");
		
	}

}
document.addEventListener("fullscreenchange", toggleFull, false);
document.addEventListener("webkitfullscreenchange", toggleFull, false);
document.addEventListener("mozfullscreenchange", toggleFull, false);

$('#wantMore').on('click', function(e){
	$('#modalMore').modal('show');
});
</script>
<?php echo $footer; ?>