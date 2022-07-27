<?php get_header() ?>
<?php
	$options = unico_WSH()->option(); 

	//Dark Color Logo Settings
	$image_logo = $options->get( 'image_normal_logo' );
	$logoUrl = $image_logo['url'];
?>
<?php if(isset($_GET['category']) && !empty($_GET['category'])) { 
    require_once plugin_dir_path( __FILE__ )."wpfaqs-category.php";
}else{ ?>
	<style>
	.elementor-2525 .elementor-element.elementor-element-3a98c2c:not(.elementor-motion-effects-element-type-background), .elementor-2525 .elementor-element.elementor-element-3a98c2c > .elementor-motion-effects-container > .elementor-motion-effects-layer {
		background-color: #000000;
	}
	</style>
    <div id="wpfaqs">
        <div class="fqs_contents">
            <div class="wpfaqs_search_module">
				<img width="110px" class="wfqs_logo" src="<?php echo $logoUrl ?>">
                <h1>How can we help you?</h1>
                <input type="text" id="faq_search_inp" placeholder="Search" @keyup="search_faq_input(event)">
                <div v-if="loadedResults.length > 0" class="faqs_search_box">
                    <ul>
                        <li v-for="(faq, index) in loadedResults" :key="index"><a :href="faq.link" v-html="faq.title"></a></li>
                    </ul>
                </div>
            </div>

            <div class="wpfaqs_categories">
                <?php
                $taxonomies = get_terms( array(
                    'taxonomy' => 'helpc',
                    'hide_empty' => false
                ) );
                
                if ( !empty($taxonomies) ) :
                    foreach( $taxonomies as $category ) {
                        ?>
                        <div class="wpfaq_cat">
                            <a href="?category=<?php echo $category->term_id ?>">
                                <?php 
                                $icon = get_term_meta ( $category->term_id, 'faq_cat_icon', true );
                                $icon = base64_decode($icon);
                                echo stripslashes($icon);
                                ?>
                                <p><?php echo $category->name ?></p>
                            </a>
                        </div>
                        <?php
                    }
                endif;
                ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php get_footer() ?>