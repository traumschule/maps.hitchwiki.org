<?php
/*
 * Hitchwiki Maps Admin: users.php
 */

if(isset($user) && $user["admin"]===true):

$sitemap_filepath = "../";
$sitemap_file = "sitemap.xml";



if(isset($_POST["create_sitemap"])) {

$sitemap = '<'.'?xml version="1.0" encoding="UTF-8"?'.'><'.'?xml-stylesheet type="text/xsl" href="sitemap.xsl"?'.'>
<!-- generator="Hitchwiki Maps" -->
<!-- sitemap-generator-url="'.$settings["base_url"].'/" sitemap-generator-version="1.0" -->
<!-- generated-on="'.date("r").'" -->
<urlset 
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
    xmlns:geo="http://www.google.com/geo/schemas/sitemap/1.0" 
    xmlns:image="http://www.sitemaps.org/schemas/sitemap-image/1.1"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" 
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">	

<url>
    <loc>'.$settings["base_url"].'/</loc>
    <changefreq>hourly</changefreq>
    <image:image>
       <image:loc>'.$settings["base_url"].'/static/gfx/badge.png</image:loc> 
    </image:image>
    <priority>1.0</priority>
</url>
';

foreach($settings["views"]["pages"] as $page => $page_settings) {
    if($page_settings["public"] == true && $page != "error404") {
$sitemap .= '<url>
    <loc>'.$settings["base_url"].'/?page='.htmlspecialchars($page).'</loc>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
</url>
';
    }
}

$sitemap .= '<url> 
    <loc>'.$settings["base_url"].'/api/?format=kml&amp;download=world&amp;all</loc>
    <changefreq>always</changefreq>
    <priority>0.5</priority>
    <geo:geo>
        <geo:format>kml</geo:format>
    </geo:geo>
</url>

</urlset>';

?><?php

if (is_writable($sitemap_filepath.$sitemap_file)) {
   
    if (!$handle = fopen($sitemap_filepath.$sitemap_file, 'w')) {
         error_sign("Couldn't creat a sitemap file! (Cannot open file ".$sitemap_filepath.$sitemap_file.")",false);
         exit;
    }
    if (fwrite($handle, $sitemap) === FALSE) {
         error_sign("Couldn't creat a sitemap file! (Cannot write to file ".$sitemap_filepath.$sitemap_file.")",false);
        exit;
    }
    
    fclose($handle);
}
else error_sign("Couldn't creat a sitemap file! (".$sitemap_filepath.$sitemap_file." is not writable)",false);

#if(file_exists($sitemap_filepath.$sitemap_file))

    $google_ping = 'http://www.google.com/webmasters/tools/ping?sitemap='.urlencode($settings["base_url"].$sitemap_file);
    $google = readURL($google_ping);
    
    #echo '<iframe src="'.$google_ping.'" style="border: 1px solid #000; margin: 20px 0; width: 600px; height: 400px;"></iframe>';
    echo '<div style="overflow: scroll; border: 1px solid #000; margin: 20px 0; width: 550px; height: 230px; padding: 20px;">'.$google.'</div>';
    
    
} //creating the sitemap

?>

<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em; margin: 20px 0;" id="info_bubble"> 
    <p><span class="ui-icon ui-icon-circle-check" style="float: left; margin-right: .3em;"></span>
    <?php if(isset($_POST["create_sitemap"])): ?>
    Created a <a href="<?php echo $sitemap_filepath.$sitemap_file; ?>" target="_blank">sitemap</a> file and submited it to <a href="http://www.google.com/support/webmasters/bin/answer.py?answer=183669">Google</a>. Re-do the task?
    <?php else: ?>
     Create a <a href="<?php echo $sitemap_filepath.$sitemap_file; ?>" target="_blank">sitemap</a> and send it to <a href="http://www.google.com/support/webmasters/bin/answer.py?answer=183669">Google</a>?
    <?php endif; ?>
    <form method="post" action="./?page=sitemap" style="display:inline;" id="create_sitemap_form">
    <input type="hidden" name="create_sitemap" value="yes" />
    <button id="create_sitemap_btn">Yes</button>
    </form></p>
</div>
<script type="text/javascript">
$(function() {

	// Yes
    $("#create_sitemap_btn").button({
        icons: {
            primary: 'ui-icon-check'
        }
    }).click(function(e) {
    	e.preventDefault();
		$("#create_sitemap_form").submit();
	});
	
});
</script>

<?php 
endif; // user check ?>