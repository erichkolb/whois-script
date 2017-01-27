<?php
if(!isset($_SESSION)) session_start();

include "../../config/config.php";

include "../../includes/functions.php";
?>
(function($){

	$.fn.invisible = function(){
	
		return this.each(function(){
		
			 $(this).css("display", "none");
		
		});  
		
	};
	$.fn.visible = function(){
	
		return this.each(function(){
		
			 $(this).css("display", "block");
		
		});
	
	};
	
}(jQuery));
$(document).ready(function() {
	$(".Search").keyup(function(event){
		if(event.keyCode == 13){
			$("#search_button").click();
		}
	});
	$('#search_button').click(function(event){
	    var url = $('#Search').val();
		if(url != '')
		{
			$.ajax({
				type: "POST",
				url: "<?php echo rootpath()?>/includes/valid_url.php",
				data: {'url':url},
				cache: false,
				dataType: "json",
				success: function(data)
				{ 
				    if(data[0] == 1)
						search_whois_detail(url,event)
					else
						$("#tooltip-error").visible();					
				}
			});
		}
	});
});

function search_whois_detail(url,e)
{
	if (!e) e = window.event;
    if (!e.ctrlKey) {
		var EventType = e.type;
		$("#tooltip-error").invisible();
		var status_of_loader = '<?php echo($_SESSION['loader_session']); ?>';
		if(status_of_loader == 1)
			$('#default-page-loader').html('<div id="preloader"><div class="loader"><div class="square" ></div><div class="square"></div><div class="square last"></div><div class="square clear"></div><div class="square"></div><div class="square last"></div><div class="square clear"></div><div class="square "></div><div class="square last"></div></div></div>').fadeIn('fast');  
		document.title = capitalise(url+" Whois Lookup and Stats");
		window.history.pushState(url, capitalise(url)+ " <?php echo str_replace(array('"'), '', get_title());?> ","<?php echo rootpath();?>/"+url+".html");
		$(".page").removeClass("active");
		$.ajax({
			type: "POST",
			url: "<?php echo rootpath()?>/show.php",
			data: {'url':url,'type':'rawdata'},
			cache: false,
			dataType: "json",
			success: function(data)
			{ 
				$('#preloader').fadeOut('slow',function(){$(this).remove();});
				if(data[0] == 0)
				{
					if(EventType == "click")
					{
						$("#tooltip-error").visible();
					}
					else
					{
						var title = '<?php echo str_replace(array('"'), '', get_title());?>';
						document.title = capitalise(title);
						window.history.pushState(title, capitalise('home')+ " <?php echo str_replace(array('"'), '', get_title());?> ","<?php echo rootpath();?>");
						$("#default-index-page").visible();
						$("#domain-info").invisible();
						$("#default-index-page").html(data[1]);
						$('.inner-mrg').responsiveEqualHeightGrid();
						$(window).scrollTop($('#default-index-page').offset().top);
					}
				}
				else
				{
					$("#default-index-page").invisible();
					$("#domain-info").visible();
					$("#whois-domain-detail").html(data[0]);
					$("#raw_registrar_data").html(data[1]);
					$(window).scrollTop($('#domain-info').offset().top);
					$.ajax({
						type: "POST",
						url: "<?php echo rootpath()?>/show.php",
						data: {'url':url,'type':'Site-Stats'},
						cache: false,
						dataType: "json",
						success: function(data)
						{ 
							if(data[0] == 0)
							{
								$("#tooltip-error").visible();
							}
							else
							{
								$("#Site-Stats").html(data[0]);
							}
						}
					});
					$.ajax({
						type: "POST",
						url: "<?php echo rootpath()?>/show.php",
						data: {'url':url,'type':'SEO-Stats'},
						cache: false,
						dataType: "json",
						success: function(data)
						{ 
							if(data[0] == 0)
							{
								$("#tooltip-error").visible();
							}
							else
							{
								$("#SEO-Stats").html(data[0]);
							}
						}
					});
				}
			}
		}); 
		return false;
	}
}
function capitalise(string) 
{
    return string;
}
function change_pages(page,title,e)
{
	if (!e) e = window.event;
    if (!e.ctrlKey) {
		var status_of_loader = '<?php echo($_SESSION['loader_session']); ?>';
		if(status_of_loader == 1)
			$('#default-page-loader').html('<div id="preloader"><div class="loader"><div class="square" ></div><div class="square"></div><div class="square last"></div><div class="square clear"></div><div class="square"></div><div class="square last"></div><div class="square clear"></div><div class="square "></div><div class="square last"></div></div></div>').fadeIn('fast');  
		document.getElementById('Search').value= '';
		if(page == 'home')
			document.title = capitalise("<?php echo str_replace(array('"'), '', get_title());?>");
		else
			document.title = capitalise(title);
		$(".page").removeClass("active");		
		$("#"+page).addClass('active');
		if(page == 'home')
			window.history.pushState(title, capitalise(page)+ " <?php echo str_replace(array('"'), '', get_title());?> ","<?php echo rootpath();?>");
		else
			window.history.pushState(title, capitalise(page)+ " <?php echo str_replace(array('"'), '', get_title());?> ","<?php echo rootpath();?>/page/"+page);
		$("#tooltip-error").invisible();
		$.ajax({		
			type:"POST",    			
			url: "<?php echo rootpath()?>/page.php",			
			data: {'page':page},			
			dataType: "json",			
			success: function(data)
			{
				$('#preloader').fadeOut('slow',function(){$(this).remove();});
				$("#domain-info").invisible();
				$("#default-index-page").visible();
				$("#default-index-page").html(data['0']);	
				if(page == 'home')
					$('.inner-mrg').responsiveEqualHeightGrid();
			}			
		}); 
		return false;
	}
}
function contact_page(page,e)
{ 
	if (!e) e = window.event;
    if (!e.ctrlKey) { 
		var status_of_loader = '<?php echo($_SESSION['loader_session']); ?>';
		if(status_of_loader == 1)
			$('#default-page-loader').html('<div id="preloader"><div class="loader"><div class="square" ></div><div class="square"></div><div class="square last"></div><div class="square clear"></div><div class="square"></div><div class="square last"></div><div class="square clear"></div><div class="square "></div><div class="square last"></div></div></div>').fadeIn('fast');  	
		document.getElementById('Search').value= '';
		document.title = capitalise('Contact Us');
		window.history.pushState("Contact Us", capitalise(page)+ " <?php echo str_replace(array('"'), '', get_title());?> ","<?php echo rootpath();?>/"+page);
		$(".page").removeClass("active");		
		$("#contact").addClass('active');
		$("#tooltip-error").invisible();
		$.ajax({	
			type:"POST",    		
			url: "<?php echo rootpath()?>/page.php",		
			data: {'page':page},		
			success: function(data)
			{
				$('#preloader').fadeOut('slow',function(){$(this).remove();});
				$("#domain-info").invisible();
				$("#default-index-page").visible();
				$("#default-index-page").html(data);				
			}			
		}); 
		return false;
	}
}
function change_recent(p,e)
{ 
	if (!e) e = window.event;
    if (!e.ctrlKey) {
		var page = 'recent';
		var status_of_loader = '<?php echo($_SESSION['loader_session']); ?>';
		if(status_of_loader == 1)
			$('#default-page-loader').html('<div id="preloader"><div class="loader"><div class="square" ></div><div class="square"></div><div class="square last"></div><div class="square clear"></div><div class="square"></div><div class="square last"></div><div class="square clear"></div><div class="square "></div><div class="square last"></div></div></div>').fadeIn('fast');  	
		document.getElementById('Search').value= '';
		document.title = capitalise("Recently Checked Websites - Page "+p);
		window.history.pushState("Contact Us", capitalise(page)+ " <?php echo str_replace(array('"'), '', get_title());?> ","<?php echo rootpath();?>/"+page+"/"+p);
		$(".page").removeClass("active");		
		$("#recent").addClass('active');
		$("#tooltip-error").invisible();
		$.ajax({	
			type:"POST",    		
			url: "<?php echo rootpath()?>/page.php",		
			data: {'page':page,'p': p},		
			success: function(data)
			{
				$('#preloader').fadeOut('slow',function(){$(this).remove();});
				$("#domain-info").invisible();
				$("#default-index-page").visible();
				$("#default-index-page").html(data);				
			}			
		}); 
		return false;
	}
}
function getParameter(theParameter) { 
	var params = window.location.search.substr(1).split('&');
	for (var i = 0; i < params.length; i++) {
		var p=params[i].split('=');
		if (p[0] == theParameter) {
			return decodeURIComponent(p[1]);
		}
	}
	return false;
}
function change_language(language,e)
{
	if (!e) e = window.event;
    if (!e.ctrlKey) {
		var currentLocation = window.location;
		$.ajax({
			type: "POST",
			url: "<?php echo rootpath()?>/change_language.php",
			data: {language: language},
			cache: false,
			success: function(result)
			{		
				window.location=currentLocation;
			}
		}); 
		return false;
	}
}
function mailsend()
{
	var name = document.getElementById('name').value;
	var email = document.getElementById('email').value;
	var subject = document.getElementById('subject').value;
	var message_box = document.getElementById('message_box').value;
	var captcha_status = '<?php echo($_SESSION['contact_captcha_status']); ?>';
	if(captcha_status == 1)
		var captcha_code = document.getElementById('captcha_code').value;
	else
		var captcha_code = '';
	var status_of_loader = '<?php echo($_SESSION['loader_session']); ?>';
	if(status_of_loader == 1)
		$('#load-message').html('<div id="preloader"><div class="loader"><div class="square" ></div><div class="square"></div><div class="square last"></div><div class="square clear"></div><div class="square"></div><div class="square last"></div><div class="square clear"></div><div class="square "></div><div class="square last"></div></div></div>').fadeIn('fast');  	
	$.ajax({
		type:"POST",    	
		url: "<?php echo rootpath()?>/contact.php",	
		data: {'name':name,'email':email,'subject':subject,'message_box':message_box,'captcha_code':captcha_code},		
		dataType: "json",		
		success: function(data)
		{
		    $('#load-message').html('');	
		    $("#replace-class").visible();	
			$(window).scrollTop($('#default-index-page').offset().top);
			if(data[1] == 'danger')
			{
				$("#replace-class").removeClass("alert alert-default alert-dismissable").addClass("alert alert-danger alert-dismissable");
				$("#replace-class").removeClass("alert alert-success alert-dismissable").addClass("alert alert-danger alert-dismissable");
				$("#alert-message").html(data[0]);	
			}
			else if(data[1] == 'success')
			{
				$("#replace-class").removeClass("alert alert-default alert-dismissable").addClass("alert alert-success alert-dismissable");
				$("#replace-class").removeClass("alert alert-danger alert-dismissable").addClass("alert alert-success alert-dismissable");
				$("#alert-message").html(data[0]);
                $('input[type="text"]').val('');				
                $('input[type="email"]').val('');				
                $('#message_box').val('');				
			}			
		}		
	});  
}
$(window).load(function(e) {
	$.ajax({
		type:"POST",
		url: "<?php echo rootpath();?>/includes/count_stats.php",
		data: {'pageViews':'1'},
	});
	<?php
	if (!isset($_SESSION['uniqueHit'])) { ?>
		$.ajax({
			type:"POST",
			url: "<?php echo rootpath();?>/includes/count_stats.php",
			data: {'uniqueHits':'1'},
		});
	<?php } ?>
});
jQuery(function($) {
	$('.inner-mrg').responsiveEqualHeightGrid(); 
});