$(document).ready(function(){
	//JCAROUSEL
	jQuery('.first-and-second-carousel').jcarousel();
	
	//SLIDE TOGGLE
	jQuery(".minicart_link").toggle(function() {
		 $('.cart_drop').slideDown(300);	
		 }, function(){
		 $('.cart_drop').slideUp(300);		 
	});	

	//SUB MENU
	jQuery("ul.departments > li.menu_cont > a").toggle(function(){
		$(this).addClass('active');
		$(this).siblings('.side_sub_menu').slideDown(300);
		}, function(){
		$(this).removeClass('active');
		$(this).siblings('.side_sub_menu').slideUp(300);
	});
	
	//FORM ELEMENTS
	jQuery("select").uniform();	
	
	//SHORTCODES
	//Toggle Box
	jQuery(".toggle_box > li:first-child .toggle_title, .toggle_box > li:first-child .toggle_content").addClass('active');
	jQuery(".toggle_box > li > a.toggle_title").toggle(function(){
														
		$(this).addClass('active');
		$(this).siblings('.toggle_content').slideDown(300);
		}, function(){
		$(this).removeClass('active');
		$(this).siblings('.toggle_content').slideUp(300);	
	});	
	
	//TWITTER FEED    //replace "rohithpaul" with your Twitter ID
	$('.twitter_feed').jTweetsAnywhere({
		username: 'rohithpaul',
		count: 1
	});
  
  $("#mobile-menu-button").click(function () {
    $("#mobile-menu").slideToggle("slow");
  });
  
  $("#browse-catalog").click(function () {
    $("#browse-catalog-div").slideToggle("slow");
  });

});