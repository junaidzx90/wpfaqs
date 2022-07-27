class WFQs_Category{
	constructor(id, label, counts){
		this.id = id;
		this.label = label;
		this.counts = counts;
	}
}
class WFQs_Faq{
	constructor(title, link, excerpt, id = null){
		this.id = id;
		this.title = title;
		this.link = link;
		this.excerpt = excerpt;
	}
}

const wfqs = new Vue({
	el: "#wpfaqs",
	data: {
		is_results: false,
		is_filtered: false,
		is_action: false,
		is_mobile_opened: true,
		category: {
			categories: [],
			selected_category: 'all',
			is_OpenSearch: false,
			loaded_categories: [],
			categoriesStorage: []
		},
		results: [],
		loadedResults: [],
		loadedInfo: '<p>Results <b>0-00</b> of <b>00</b> in <b>0.00</b> seconds</p>',
		perpage: 10,
		page: 1,
		totalRows: 0,
		minResult: 1,
		maxResult: 5
	},
	methods: {
		toggle_sidemenu: function(){
			if(this.is_mobile_opened){
				this.is_mobile_opened = false;
			}else{
				this.is_mobile_opened = true;
			}
		},
		nextPagination_page: function(){
			this.minResult += 5;
			this.maxResult += 5;
		},
		prevPagination_page: function(){
			this.minResult -= 5;
			this.maxResult -= 5;
		},
		changePerpage: function(count){
			this.perpage = count;
			this.get_initial_results("filtered");
		},
		pagination: function(count){
			this.page = count;
			this.get_initial_results("filtered");
		},
		millisToMinutesAndSeconds: function(millis) {
			var minutes = Math.floor(millis / 60000);
			var seconds = ((millis % 60000) / 1000).toFixed(0);
			return minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
		},
		get_search_result: function(){
			this.get_initial_results("filtered");
		},
		get_initial_results: function(which, perpage = wfqs.perpage, page = wfqs.page){
			const queryString = window.location.search;
			const urlParams = new URLSearchParams(queryString);
			const category = urlParams.get('category');

			jQuery.ajax({
				type: "get",
				url: wfqsajax.ajaxurl,
				startTime: performance.now(),
				data: {
					action: "get_initial_results",
					category: category,
					which: which,
					perpage: perpage,
					page: page,
					nonce: wfqsajax.nonce
				},
				beforeSend: () =>{
					wfqs.is_results = false;
				},
				dataType: "json",
				success: function (response) {
					wfqs.is_results = true;
					if(response.categories){
						response.categories.forEach(cat => {
							wfqs.category.categories.push(new WFQs_Category(cat.id, cat.label, cat.counts));
						});
					}
					if(response.faqs){
						wfqs.results = [];
						response.faqs.forEach(faq => {
							wfqs.results.push(new WFQs_Faq(faq.title, faq.link, faq.excerpt));
						});
					}

					if(response.counts){
						wfqs.totalRows = response.counts;
					}

					//Calculate the difference in milliseconds.
					var time = performance.now() - this.startTime;

					//Convert milliseconds to seconds.
					var seconds = time / 1000;
					//Round to 3 decimal places.
					seconds = seconds.toFixed(2);
					//Write the result to the HTML document.
					var result = `<p>Pages <b>${page}-${Math.ceil(wfqs.totalRows / wfqs.perpage)}</b> faqs <b>${wfqs.totalRows}</b> in <b>${seconds}</b> seconds</p>`;
					wfqs.loadedInfo = result;

					wfqs.is_results = true;
				}
			});
		},
		selectCategory: function(id){
			const url = new URL(window.location);
			url.searchParams.set('category', id);
			window.history.pushState({}, '', url);
			this.category.selected_category = id;
			this.page = 1;
			this.minResult = 1;
			this.maxResult = 5;

			if(jQuery(".wfq_categories").css("position") === 'absolute'){
				wfqs.is_mobile_opened = false;
			}

			this.is_action = true;
		},
		get_all_data: function(){
			this.category.selected_category = 'all';
			const url = new URL(window.location);
			url.searchParams.set('category', "all");
			window.history.pushState({}, '', url);
			this.page = 1;
			this.minResult = 1;
			this.maxResult = 5;
			
			this.is_action = true;
		},
		opening_category_search: function(){
			this.category.is_OpenSearch = true;
			// Ajax request
			jQuery.ajax({
				type: "get",
				url: wfqsajax.ajaxurl,
				data: {
					action: "get_search_category",
					nonce: wfqsajax.nonce,
					search: jQuery("#wfq_search").val()
				},
				dataType: "json",
				success: function (response) {
					jQuery("#wfq_search").focus();

					if(response.categories){
						response.categories.forEach(cat => {
							wfqs.category.loaded_categories.push(cat);
						});
					}
				}
			});
		},
		search_category_input: function(e){
			let search = jQuery(e.target).val();
			if(this.category.categoriesStorage.length === 0) this.category.categoriesStorage = this.category.loaded_categories;

			let categories = this.category.categoriesStorage.filter(el => {
				return el.label.toLowerCase().indexOf(search.toLowerCase()) != -1;
			});

			this.category.loaded_categories = categories;

			if(search === ""){
				this.category.loaded_categories = this.category.categoriesStorage;
			}
		},
		search_faq_input: function(e){
			let search = jQuery(e.target).val();
			
			jQuery.ajax({
				type: "get",
				url: wfqsajax.ajaxurl,
				data: {
					action: "get_faq_by_search",
					nonce: wfqsajax.nonce,
					search: search
				},
				dataType: "json",
				success: function (response) {
					wfqs.loadedResults = [];
					if(response.faqs){
						response.faqs.forEach(faq => {
							wfqs.loadedResults.push(new WFQs_Faq(faq.title, faq.link, '', faq.id))
						})
					}
				}
			});
		}
	},
	updated: function(){
		// Clicking outside
		// Hiding category search box
		jQuery("#wfq_search").blur(function(){
			setTimeout(() => {
				wfqs.category.is_OpenSearch = false;
				wfqs.category.loaded_categories = [];
			}, 200);
		});

		jQuery("#faq_search_inp").blur(function(){
			setTimeout(() => {
				wfqs.loadedResults = [];
			}, 200);
		});
		
		if(this.is_action){
			this.get_search_result();
			this.is_action = false;
		}
		
	},
	mounted: function () {
		setTimeout(() => {
			this.get_initial_results('initial');
			const queryString = window.location.search;
			const urlParams = new URLSearchParams(queryString);
			const category = urlParams.get('category')
			this.category.selected_category = category;
		}, 500);
	}
});