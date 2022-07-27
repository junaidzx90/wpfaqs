<?php get_header() ?>
<?php if(isset($_GET['category']) && !empty($_GET['category'])) { 
    require_once plugin_dir_path( __FILE__ )."wpfaqs-category.php";
}else{ ?>
    <div id="wpfaqs">
        <div class="fqs_contents">
            <div class="wpfaqs_search_module">
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