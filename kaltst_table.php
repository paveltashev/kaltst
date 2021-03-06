<?php
require_once(plugin_dir_path( __FILE__ ).'lib/KalturaClient.php');
require_once(plugin_dir_path( __FILE__ ).'kaltst-embed-player.php');
add_action('kal_list_hook', 'kaltst_lst');
function kaltst_lst() {
    foreach ( glob( plugin_dir_path( __FILE__ ) . "lib/js/datatable/*.js" ) as $file ) {
        $url = plugins_url( wp_basename( $file ), "/kaltst/lib/js/datatable/*.js");
        echo "<script type='text/javascript' src='". $url . "'></script>"."\r\n";
    }
    foreach (glob( plugin_dir_path( __FILE__ ) . "lib/css/datatable/*.css" ) as $csss ) {
        $url = plugins_url( wp_basename( $csss ), "/kaltst/lib/css/datatable/*.css");
        echo "<link rel='stylesheet' type='text/css' href='".$url ."'>"."\r\n";
    }
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
        $serviceUri = KALTST_SERVICE_URL;
        $partnerr   = KALTST_PARTNER_ID;
        do_action('hook_javascript');
        $instance = new KalturaListPlugin();
	      $objEntry = $instance->the_content();
    ?>
<script>
    var KALTURA_SERVICE_URL = '<?php echo KALTST_SERVICE_URL ?>';
    var KALTURA_PARTNER_ID  = '<?php echo KALTST_PARTNER_ID ?>';
    var KALTURA_PARTNER_UI_CONFIG  = '<?php echo KALTST_PLAYER_UI_CONFIG ?>';
</script>
<div class="container frm_table wrap">
    <div class="row">
<form action="" method="post" id='myForm' role="form">
            <table id="kaltura"  class="table table-striped table-hover dataTable no-footer collapsed" style="overflow: scroll" cellspacing="0" >
                <thead>
                    <tr>
                        <td>Идентификационнен номер</td>
                        <td>категория</td>
                        <td>Заглавие</td>
                        <td>Дата</td>
                        <td>Изображение</td>
                        <td>Постави в пост</td>
                    </tr>
                </thead>
                <tbody>
            <?php foreach ($objEntry as $mediaEntry) : ?>
                <tr id="<?php echo $mediaEntry->id ?>">
                        <td class="sometest" style="vertical-align:middle; padding: 2%;"><h5 class="blockquote"><?php echo $mediaEntry->id;?></h5></td>
                        <td style="vertical-align:middle;"><h4 class="blockquote" ><?php echo $mediaEntry->categories;?></h4></td>
                        <td style="vertical-align:middle;"><h3 class="blockquote" style='margin-left:auto; margin-right:auto;'><?php echo $mediaEntry->name; ?></h3></td>
                        <td style="vertical-align:middle;"><span><h4 class="blockquote" style='margin-left:auto; margin-right:auto;'><?php echo date('d-m-Y H:i:s', $mediaEntry->createdAt); ?></h4></span></td>
                        <td><?php echo "<img  width='300px' height='140px' src='".KALTST_SERVICE_URL."/p/".KALTST_PARTNER_ID."/thumbnail/entry_id/".$mediaEntry->id."/width/300/height/140/type/1/quality/100' onmouseover='KalturaThumbRotator.start(this)' onmouseout='KalturaThumbRotator.end(this)'/>"; ?></td>
                <td style="vertical-align:middle;  padding: 2%;">
                        <input type="radio" id="someval" class="someval" name="someval" value="<?php echo $mediaEntry->id ;?>"  onclick="callFunc(this)">
                </td>
                </tr>
            <?php endforeach; ?>
                    </tbody>
                </table>
    <div class="mceActionPanel">
      <button type="button" class="btn btn-primary" name="insert">Post Video</button>
      <button type="button" class="btn btn-danger" id="delet" name="delet">Delete Video</button>
    </div>
    </form>
</div>
</div>
  <div id='myModal' class='modal fade' role='dialog'>
    <form action="" method="post" id='myModalForm' role="form">
  			<div class="modal-dialog">
  		   		<div class="modal-content">
  		      		<div class="modal-header" id="zaglavieto">
  		        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  		       		 	<!--<h4 class="modal-title">Send Message</h4>-->
  		      		</div>
  			      	<div class="modal-body" id="modd">
  						<form role="form" id="send_message_form">
  							<div class="callout callout-danger error_message" style="display:none"></div>
<div class="form-group" id="frm_emebed_code">
  <div style='width: 100%;display: inline-block;position: relative;'>
    <div style='margin-top: 56.25%;'>
      <?php echo "<script src='".KALTST_SERVICE_URL."p/".KALTST_PARTNER_ID."/sp/".KALTST_PARTNER_ID."00/embedIframeJs/uiconf_id/".KALTST_PLAYER_UI_CONFIG."/partner_id/".KALTST_PARTNER_ID."'></script>"; ?>
      <div id='kaltura_player_1437197987' style='position:absolute;top:0;left:0;left: 0;right: 0;bottom:0;' itemprop='video' itemscope itemtype='http://schema.org/VideoObject'>
      </div>
    </div>
  </div>
</div>
  						</form>
              <form role="form" id="frm_code_snipet">
                <div class="form-group">
                  <?prettify lang=html linenums=true?>
                  <pre class="wb-prettify" id="quine" style="border:4px solid #88c; white-space: pre-wrap; word-wrap: break-word;">
                  </pre>
                </div>
              </form>
  					</div>
  			       	<div class="modal-footer" style="margin-top:0;">
  			      		<div style="float:left;"><button type="button" class="btn btn-default btncopy btn_form_show" id="btncopy">advanced</button></div>
  			      		<div style="float:right">
  						            <button type="button" class="btn btn-danger" onclick="stopVideo()" id="closing" data-dismiss="modal">Cancel</button>
  			     	            <button type="submit" class="btn btn-primary embedbutton" name="embedbutton" id="embedbutton" data-loading-text="Submitting...">Embed In Post</button>
  			     	    </div>
  			      	</div>
  		    	</div>
  		  	</div>
          </form>
  		</div>
      <script>
      var KALTURA_MEDIA_ENTRY_ID = $("#myModal").attr("data_id");
      someFunction();
        function callFunc(elem){
            document.getElementById(KALTURA_MEDIA_ENTRY_ID).checked = elem.checked;
        }
        $(document).ready(function() {
            $( "#frm_code_snipet" ).is( ":hidden" );
            formEmbedDropDown();
        });
          select_all_text();
      </script>
    <?php
    if ($_POST['someval']) {
      define(MEDIAENDTRY, $_POST['someval']);
    }elseif ($_POST['embedbutton']) {
      define(MEDIAENDTRY, $_POST['embedbutton']);
    }
    do_action("admin_footer_text");
    if(isset($_POST['insert']))
    {
                if (isset($_POST['someval'])) {
                }
                    $instance = new KalturaListPlugin();
                    $objEntryData = $instance->the_mediaentry();
                    $content_embed = do_shortcode( '[mh-tripadvisor]' );
        $post_obj = array(
                        'post_title' => $objEntryData->name,
                        'post_content' => $content_embed,
                        'post_category' => array(1), //Uncategorized
                        'post_status' => 'draft',
                        'post_author' => 1 //Admin
                       );
        $id = wp_insert_post($post_obj);
        if(isset($id) && !is_wp_error($id))
        {
            //wp_redirect(home_url('/thank-you'));
            ?><script type="text/javascript">
        alert("<?php print_r($objEntryData->name) ?>");
        </script><?php
            exit;
        }
    }
    elseif(isset($_POST['embedbutton']))
    {
        $instance = new KalturaListPlugin();
        $objEntryData = $instance->the_mediaentry();
        $content_embed = do_shortcode( '[mh-tripadvisor]' );
        $post_obj = array(
                        'post_title' => $objEntryData->name,
                        'post_content' => $content_embed,
                        'post_category' => array(1), //Uncategorized
                        'post_status' => 'draft',
                        'post_author' => 1 //Admin
                       );
        $id = wp_insert_post($post_obj);
        if(isset($id) && !is_wp_error($id))
        {
            //wp_redirect(home_url('/thank-you'));
            ?><script type="text/javascript">
        alert("<?php print_r($objEntryData->name) ?>");
        </script><?php
            exit;
        }
    }
?>
<?php
}
function remove_footer_admin ()
{
    remove_filter( 'update_footer', 'core_update_footer' );
    remove_action( 'network_admin_notices', 'update_nag', 3 );
}
add_filter('admin_footer_text', 'remove_footer_admin');
?>
