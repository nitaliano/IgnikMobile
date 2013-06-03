/**
 * Written by: Nick Italiano
 * This is a work in progress for the mobile version of
 * http://www.ignikdesign.com
 */


$(function(){

/*================================================
				ROUTER
 =================================================*/

	
/**
 * Router for the webapp.
 * Contains the possible routes avaliable to app
 */
var IgnikRouter = Backbone.Router.extend({
	
	/**
	 * Possible routes
	 */
	routes: {
		'': '',
		'home': 'home',
		'portfolio': 'portfolio',
		'about': 'about',
		'contact': 'contact'
	},
	
	
	/**
	 * Grabs the current route in the url after #
	 * For example : ignikdesign.com/#portfolio -> portfolio
	 * 
	 * @returns current route in the url
	 */
	current: function(){
		var url = window.location.href.toString().split(window.location.host)[1].split('/');
		return url[url.length-1].replace('#','');
	}
});





/*================================================
  				MODELS
 =================================================*/


var RouteModel = Backbone.Model.extend({
	defaults:{
		url: undefined
	}
});	

var PortfolioModel = Backbone.Model.extend({
	
	defaults:{
		imagelink: '',
		likekey: '',
		likesforimg: 0
	}
});





/*================================================
				COLLECTIONS
 =================================================*/


var PortfolioCollection = Backbone.Collection.extend({
	
	models: [],
	
	url: 'scripts/portfolio-data.php',
	
	parse: function(res){		
		var jsonObj = res;
		
		for(var key in jsonObj){
			this.models.push(new PortfolioModel({
				imagelink: 'portfolio-images/' + key,
				likekey: key,
				likesforimg: jsonObj[key].likes
			}));
		}
	},
	
	get: function(index){
		return this.models[index].attributes;
	}
});





/*================================================
					VIEWS
 =================================================*/


/**
 * Handles the navigation,
 * by switching the templates
 * based on the current route
 */
var ContentView = Backbone.View.extend({
	
	
	/**
	 * Main content container, this is where the templates get added too
	 */
	el : $('.page'),
	
	
	/**
	 * Init the ContentView to change template
	 * based on route
	 * 
	 * @param router - Context of IgnikRouter
	 * @param app - Context of IgnikAppView
	 */
	initialize: function(router, app){
		this.router = router;
		
		Backbone.history.on('route', function(source, route){
			app.model.set({url: this.router.current()});
		}, this);
	},
	
	
	/**
	 * Templates can be found in index.html
	 */
	content:{
        '': _.template(document.getElementById('home').innerHTML),
        'home': _.template(document.getElementById('home').innerHTML),
        'portfolio': _.template(document.getElementById('portfolio').innerHTML)
    },
    
    
    /**
     * Sets the template in the parent container
     * 
     * @param key - Route
     */
	render: function(key){
		this.$el.html(this.content[key]);
	}
});	



/**
 * View for the Top Navigation, it appears on every page
 */
var TopNavView = Backbone.View.extend({
	
	/**
	 * Init navigation buttons
	 */
    initialize: function(){
    	this.$navBtn = $('.nav-btn');
    },
    
    events:{
        'click .nav-btn': 'onNavButtonClick'
    },
    
    onNavButtonClick: function(e){
    	e.preventDefault();
    	var route = $(e.currentTarget).attr('href');
    	Backbone.history.navigate(route, {trigger:true});
    	return this;
    }
});



/**
 * View for Coinslider on homepage
 */
var CoinSliderView = Backbone.View.extend({
	
	/**
	 * Init the coinslider and setup listeners for swipe events
	 * @param images - List of images used for coinslider
	 */
	initialize: function(images){
		this.images = images;
		this.coinIndex = 0;
				
		var hammertime = $('.ignik-wrapper').hammer();
		
		hammertime.on('swipeleft', '.mobile-coinslider', function(e){
			self.captureRightIndex();
		});
		
		hammertime.on('swiperight', '.mobile-coinslider', function(e){
			self.captureLeftIndex();
		});
	},
	
	
	/**
	 * Capture the coinIndex for swipeleft to
	 * make sure we are using the expected value
	 */
	captureLeftIndex: function(){
		if(this.coinIndex == 0){
			this.coinIndex = 4;
		} else {
			this.coinIndex--;
		}
		
		this.animateSlider(this.coinIndex);
	},
	
	
	/**
	 * Capute the coinIndex for swiperight to
	 * make sure we are using the expected value
	 */
	captureRightIndex: function(){
		if(this.coinIndex == 4){
			this.coinIndex = 0;
		} else {
			this.coinIndex++;
		}
		
		this.animateSlider(this.coinIndex);
	},
	
	start: function(){
		var self = this;
		
		this.interval = setInterval(function() {
			if(self.coinIndex == 4){
				self.coinIndex = 0;
				self.animateSlider(self.coinIndex);
			} else{
				self.coinIndex++;
				self.animateSlider(self.coinIndex);
			}
			
			console.log(self.coinIndex);
		}, 3000);
		
		return this;
	},
	
	stop: function(){
		clearInterval(this.interval);
	},
	
	animateSlider: function(index){
		$('.mobile-coinslider img').attr('src', this.images[index]);
	}
});



/**
 * View for Portfolio, appears on portfolio page
 */
var PortfolioView = Backbone.View.extend({
	
	
	/**
	 * Init the portfolio, and fetch the data
	 * from the collection
	 */
	initialize: function(){
		var self = this;
		
		this.collection = new PortfolioCollection();
		this.collection.fetch({
			success: function(results){
				if(typeof results !== 'undefined'){
					self.render(results);
				}
			}
		});
	},
	
	render: function(portfolio){
		var template = _.template(document.getElementById('portfolio-piece').innerHTML);
				
		for(var i = 0; i < _.size(portfolio.models); i++){
			$('.portfolio-list').append(template(portfolio.get(i)));
		}
	}
}); 



/**
 * Master View
 */
var IgnikAppView = Backbone.View.extend({
	
	model : new RouteModel(),
	
	initialize: function(){
		// init router and content view for app
		this.router = new IgnikRouter();
		this.contentView = new ContentView(this.router, this);
		
		// init top nav bar
		this.topNavView = new TopNavView();
						
		// init coinslider
		this.coinsliderView = new CoinSliderView([
			"images/IGNIKDESIGNCARDSscreenshot.jpg",
			"images/yapit_coinslider.jpg",
			"images/emiko_coinslider.jpg","images/loopscreenshot.jpg",
			"images/clockscreenshot.jpg"
		]);
		
		// init listener for route model change
		this.listenTo(this.model, 'change', this.render);
	},
	
	render: function(){	
		// render template for route selected
		this.contentView.render(this.model.get('url'));
		
		// check url to start, or stop coin slider
		if(this.model.get('url') == '' || this.model.get('url') == 'home'){
			this.coinsliderView.start();
		} else {
			this.coinsliderView.stop();
		}
		
		// check url to see if it is time to fetch the portfolio data
		if(this.model.get('url') == 'portfolio'){
			this.portfolioView = new PortfolioView();
		}
	}
	
});

new IgnikAppView();
Backbone.history.start();
});