<?php get_header() ?>
<style>
.elementor-2525 .elementor-element.elementor-element-3a98c2c:not(.elementor-motion-effects-element-type-background), .elementor-2525 .elementor-element.elementor-element-3a98c2c > .elementor-motion-effects-container > .elementor-motion-effects-layer {
    background-color: #000000;
}
</style>
<div id="wpfaqs">
    <div id="wpfaqs_skleton" v-if="is_results === false">
        <div class="fqs_contents">
            <div class="wpfaqs_search_module">
                <div class="skleton half"></div>
                <div class="skleton"></div>
            </div>

            <div class="search_module">
                <div class="wfq_categories skeletoncats">
                    <div class="skleton"></div>
                    <div class="wfq_catlist">
                        <div class="skleton_list">
                            <div class="skleton_squre skleton"></div>
                            <div class="skleton"></div>
                        </div>
                        <div class="skleton_list">
                            <div class="skleton_squre skleton"></div>
                            <div class="skleton"></div>
                        </div>
                        <div class="skleton_list">
                            <div class="skleton_squre skleton"></div>
                            <div class="skleton"></div>
                        </div>
                        <div class="skleton_list">
                            <div class="skleton_squre skleton"></div>
                            <div class="skleton"></div>
                        </div>
                    </div>
                </div>

                <div class="wfq_contents">
                    <div class="results_head">
                        <div class="skleton_selected_category selected_category">
                            <div class="skleton"></div>
                        </div>
                        <div class="skleton btn"> </div>
                    </div>
                    <div class="results_info place"></div>

                    <div class="contents_list">
                        <div class="wfq_list_item">
                            <div class="skleton half"></div>
                            <div class="skleton"></div>
                            <div class="skleton"></div>
                            <div class="skleton ext-half"></div>
                        </div>
                        <div class="wfq_list_item">
                            <div class="skleton half"></div>
                            <div class="skleton"></div>
                            <div class="skleton"></div>
                            <div class="skleton ext-half"></div>
                        </div>
                        <div class="wfq_list_item">
                            <div class="skleton half"></div>
                            <div class="skleton"></div>
                            <div class="skleton"></div>
                            <div class="skleton ext-half"></div>
                        </div>
                    </div>

                    <div class="wfq_paginations">
                        <div class="skleton ext-half"></div>
                        <div class="skleton ext-half"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="wpfaqs_data" v-if="is_results">
        <div class="fqs_contents">
            <div class="wpfaqs_search_module">
                <div class="top_search_box">
                    <img width="110px" class="wfqs_logo" src="<?php echo $logoUrl ?>">
                    <h1>How can we help you?</h1>
                    <input type="text" id="faq_search_inp" placeholder="Search" @keyup="search_faq_input(event)">
                    <div v-if="loadedResults.length > 0" class="faqs_search_box">
                        <ul>
                            <li v-for="(faq, index) in loadedResults" :key="index"><a :href="faq.link" v-html="faq.title"></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="search_module">
                <div v-show="is_mobile_opened" class="wfq_categories">
                    <div class="cat_head">
                        <h3>Category</h3>
                        <button v-show="category.selected_category !== 'all'" @click="get_all_data()" class="clear_search">+</button>
                    </div>
                    <div class="wfq_catlist">
                        <div v-show="category.selected_category !== 'all'" @click="get_all_data()" class="litem"><span class="leftarrow">❮</span>
                            <span class="termname">All Categories</span>
                        </div>

                        <div v-for="(cat, index) in category.categories" :key="index" v-if="index <= 10" @click="selectCategory(cat.id)" :class="category.selected_category == cat.id ? 'litem selected': 'litem'">
                            <span class="termname">{{cat.label}}</span>
                            <span class="counts">{{cat.counts}}</span>
                        </div>
                    </div>
                    <div class="search_box">
                        <?php 
                        // Clicking search button
                        // Hide/Show if search box opened
                        ?>
                        <div @click="opening_category_search()" v-show="!category.is_OpenSearch" class="search_btn">
                            <span class="wfqs_plus_icon">+</span>
                            <span>Search</span>
                        </div>
                        <div class="search_inp" v-if="category.is_OpenSearch">
                            <label for="wfq_search" class="sicon animated_loader">
                                <?php 
                                // Showing loading icon if categories is loaded
                                ?>
                                <svg v-show="category.loaded_categories.length === 0" alt="Loading" focusable="false" enable-background="new 0 0 18 18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" class="wfq-search-wait-animation-svg"><g fill="currentColor"><path d="m16.76 8.051c-.448 0-.855-.303-.969-.757-.78-3.117-3.573-5.294-6.791-5.294s-6.01 2.177-6.79 5.294c-.134.537-.679.861-1.213.727-.536-.134-.861-.677-.728-1.212 1.004-4.009 4.594-6.809 8.731-6.809 4.138 0 7.728 2.8 8.73 6.809.135.536-.191 1.079-.727 1.213-.081.02-.162.029-.243.029z"></path><path d="m9 18c-4.238 0-7.943-3.007-8.809-7.149-.113-.541.234-1.071.774-1.184.541-.112 1.071.232 1.184.773.674 3.222 3.555 5.56 6.851 5.56s6.178-2.338 6.852-5.56c.113-.539.634-.892 1.184-.773.54.112.887.643.773 1.184-.866 4.142-4.57 7.149-8.809 7.149z"></path></g></svg>
                                <?php 
                                // Showing before loaded categories loaded
                                ?>
                                <svg v-show="category.loaded_categories.length > 0" alt="Search" focusable="false" enable-background="new 0 0 20 20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="wfq-search-magnifier-svg"><g fill="currentColor"><path class="wfqs-magnifier-circle-svg" d="m8.368 16.736c-4.614 0-8.368-3.754-8.368-8.368s3.754-8.368 8.368-8.368 8.368 3.754 8.368 8.368-3.754 8.368-8.368 8.368m0-14.161c-3.195 0-5.793 2.599-5.793 5.793s2.599 5.793 5.793 5.793 5.793-2.599 5.793-5.793-2.599-5.793-5.793-5.793"></path><path d="m18.713 20c-.329 0-.659-.126-.91-.377l-4.552-4.551c-.503-.503-.503-1.318 0-1.82.503-.503 1.318-.503 1.82 0l4.552 4.551c.503.503.503 1.318 0 1.82-.252.251-.581.377-.91.377"></path></g></svg>
                            </label>
                            <input @keyup="search_category_input(event)" type="search" class="wfq_search" id="wfq_search">
                        </div>
                        <div v-if="category.loaded_categories.length > 0" class="search_popup">
                            <ul>
                                <li v-for="(cat, index) in category.loaded_categories" :key="index" @click="selectCategory(cat.id)">{{cat.label}}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="wfq_contents">
                    <?php 
                    // Showing if filter data filtered
                    ?>
                    <div v-if="is_filtered" class="results_head">
                        <div class="selected_category">
                            <span>Category: <b>cPanel</b></span>
                            <span class="clear_search">+</span>
                        </div>
                        <div class="clearbtn_box">
                            <button class="clear_search">Clear all filters</button>
                        </div>
                    </div>

                    <div class="results_info">
                        <button @click="toggle_sidemenu()" class="sidebar_toggle"><img src='data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTAwMCAxMDAwIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAxMDAwIDEwMDAiIGZpbGw9IiM1YTVhNWEiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPG1ldGFkYXRhPiBTdmcgVmVjdG9yIEljb25zIDogaHR0cDovL3d3dy5vbmxpbmV3ZWJmb250cy5jb20vaWNvbiA8L21ldGFkYXRhPg0KPGc+PHBhdGggZD0iTTk0MSw0NTFINTljLTI5LjQsMC00OSwxOS42LTQ5LDQ5czE5LjYsNDksNDksNDloODgyYzI5LjQsMCw0OS0xOS42LDQ5LTQ5Uzk3MC40LDQ1MSw5NDEsNDUxTDk0MSw0NTF6IE05NDEsODQzSDU5Yy0yOS40LDAtNDksMTkuNi00OSw0OXMxOS42LDQ5LDQ5LDQ5aDg4MmMyOS40LDAsNDktMTkuNiw0OS00OVM5NzAuNCw4NDMsOTQxLDg0M0w5NDEsODQzeiBNNTksMTU3aDg4MmMyOS40LDAsNDktMTkuNiw0OS00OXMtMTkuNi00OS00OS00OUg1OWMtMjkuNCwwLTQ5LDE5LjYtNDksNDlTMjkuNiwxNTcsNTksMTU3TDU5LDE1N3oiLz48L2c+DQo8L3N2Zz4='/></button>
                        <div class="infocontents" v-html="loadedInfo"></div>
                    </div>

                    <div class="contents_list">
                        <div v-for="(item, index) in results" :key="index" class="wfq_list_item">
                            <h3><a :href="item.link" v-html="item.title"></a></h3>
                            <p v-html="item.excerpt"></p>
                        </div>

                        <div v-if="results.length === 0" class="no_results">No results!!</div>
                    </div>

                    <div class="wfq_paginations">
                        <div v-if="totalRows > perpage" class="result_pagination">
                            <span @click="prevPagination_page()" v-show="minResult > 1" class="wfq_left">❮</span>
                            <ul>
                                <li v-for="num in Math.ceil(totalRows/perpage)" v-show="num >= minResult && num <= maxResult" :class="page === num?'active': ''" @click="pagination(num)">{{num}}</li>
                            </ul>
                            <span @click="nextPagination_page()" v-show="maxResult < Math.ceil(totalRows/perpage)" class="wfq_right">❯</span>
                        </div>
                        <div class="result_per_page">
                            <p>Results per page</p>
                            <ul>
                                <li :class="perpage === 10?'active': ''" @click="changePerpage(10)">10</li>
                                <li :class="perpage === 50?'active': ''" @click="changePerpage(50)">50</li>
                                <li :class="perpage === 100?'active': ''" @click="changePerpage(100)">100</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer() ?>