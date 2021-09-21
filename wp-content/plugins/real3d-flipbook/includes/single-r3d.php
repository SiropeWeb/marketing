<?php 

$post_id = get_the_ID();
$id = get_post_meta($post_id, 'flipbook_id', true);
$flipbook = get_option('real3dflipbook_' . $id);
if(isset($flipbook['mode']) && $flipbook['mode'] == "fullscreen")
	?>
	<style>header, footer{display:none;}</script>
	<?php
get_header();
echo do_shortcode('[real3dflipbook id="'.$id.'"]');
get_footer();





