/**
 * Page Url (Compatible with HTTPS)
 * 
 * @type {string}
 */
var url = "https:" == document.location.protocol 
			? "https://" + document.location.host   + '/' 
			: "http://"  + document.location.host   + '/';

/**
 * Application Variable
 * 
 * @type {Object}
 */
var app = {
	'url' 		: url,
	'ajaxUrl'	: url + 'ajax/',
	'uploadify' : url + '/bundles/uploadify'
}

/**
 * Fetch the body id name.
 * 
 * @type {string}
 */
var body = {
	'id' 	: $('body').attr('id'),
	'class' : $('body').attr('class') 
}

/*
 * Disable all autocomplete on text input form elements.
 */
$('input[type=text]').attr('autocomplete', 'off');

/**
 * Sroll to anchor tag on the page
 */
function scrollToTag()
{
	/**
	 * Prevent anchor tag links from being hid behind the navbar.
	 */
	if( window.location.hash )
	{
	    // Wherever the anchor tag link is, move it 58 more pixels further down.
	    $('html,body').animate({
	        scrollTop:$('[name="'+window.location.hash.substring(1)+'"]').offset().top - 58
	    }, 500);
	}
}

// Automaically scroll to tag on page load.
scrollToTag();


// @source http://stackoverflow.com/questions/680785/on-window-location-hash-change
if (("onhashchange" in window) && !($.browser.msie)) {
     window.onhashchange = function () {
          //alert(window.location.hash);
          scrollToTag();
     }
     // Or $(window).bind( 'hashchange',function(e) {
     //       alert(window.location.hash);
     //   });
}
else {
    var prevHash = window.location.hash;
    window.setInterval(function () {
       if (window.location.hash != prevHash) {
          storedHash = window.location.hash;
          // alert(window.location.hash);
          scrollToTag();
       }
    }, 100);
}


/**
 * Check for particular page match based upon the body id and class value.
 * 
 * @param  {string} $id    The <body id="">
 * @param  {string} $class  The <body class="">
 * @return {boolean}       True = match, False = no match
 */
function matches(bodyId, bodyClass){
	return (body.id === bodyId && body.class === bodyClass)
}

/**
 * Return url to bundle asset.
 * 
 * @param  {string} bundle The registered bundle name.
 * @param  {string} type   The type of asset (css, js)
 * @param  {string} name   The file name.
 * @return {string}        The full url path to the asset.
 */
function bundleAsset(bundle, type, name){
	return app.url + 'bundles/' + bundle + '/' + type + '/' + name;
}

/**
 * Scroll to the bottom of the page.
 */
$('.scrollToBottom').click(function(){
    $('html, body').animate({scrollTop:$(document).height()}, 'slow');
    return false;
});

switch(true)
{
	// Assign members to project page.
	case matches('project', 'members_assign'):

		// Search ldap directory for user account.
		$('#submitLdapSearch').on('click', function(event){

			// Disable standard click event
			event.preventDefault();

			// Capture the project id
			var project_id = $(this).attr('project_id');

			// Capture the search input
			var searchInput = $(this).closest('form').find('input[name="name"]').val();

			// Submit ajax request.
			$.getJSON(app.ajaxUrl + 'ldap-search/' + project_id, { name : searchInput}, function( response )
			{
				// Prepare response output
				var responseOutput = {
					'count'	  : response.length,
					'results' : response
				};

				// HTML container
				var html = '';

				// We have records in the results set.
				if( responseOutput.count )
				{
					// Remove existing rows
					$('tbody.ajaxResults').find('tr').remove();

					// Start looping through the results.
					for(var i = 0; i <= responseOutput.count - 1; i++ )
					{
						var actionButton = $('<button/>', {
							'class' : 'btn btn-small btn-block assignMember',
							'html'	: '<i class="icon-plus"></i> Assign',
							'row'	: i,
						});

						var name = responseOutput.results[i].name;

						var hidden = $('<input/>', {
							'name' 	: 'user['+i+'][name]',
							'value'	: name,
							'type'	: 'hidden'
						});

						var leader = $('<input/>', {
							'name' : 'user['+i+'][leader]',
							'type' : 'checkbox'
						});

						html += '<tr>' +
									'<td>' + name + hidden[0].outerHTML + '</td>' +
									'<td style="text-align:center">' + leader[0].outerHTML + '</td>' +
									'<td>' + actionButton[0].outerHTML + '</td>' +
								'</tr>';
					}

					// Append the html to the page.
					$('tbody.ajaxResults').append(html);
				}

				// No records returned.
				else
				{
					html += '<tr>' +
								'<td colspan="3">Your search returned zero results.</td>' +
							'</tr>';

					// Append the html to the page.
					$('tbody.ajaxResults').append(html);
				}
			});
		});

	break;

	// Edit Project Page
	case matches('project', 'create'):
	case matches('project', 'update'):

		// Enable nicedit on all textarea html elemets
		//bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });

		// Create new instance of nicEdit
		var editorInstance = new nicEditor({
			// Set which buttons we want to appear
			buttonList : [
				'bold', 
				'italic', 
				'underline', 
				'left', 
				'center', 
				'right', 
				'ol', 
				'ul', 
				'link', 
				'removeformat', 
				'indent', 
				'outdent', 
				'fontSize',
				'fontFamily',
				'fontFormat',
				'xhtml'
			]
		}).panelInstance('description');
	break;
	break;

	case matches('project', 'create_discussion'):
	case matches('project', 'discussion'):

		// Enable nicedit on all textarea html elemets
		//bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });

		// Create new instance of nicEdit
		var editorInstance = new nicEditor({
			// Set which buttons we want to appear
			buttonList : [
				'bold', 
				'italic', 
				'underline', 
				'left', 
				'center', 
				'right', 
				'ol', 
				'ul', 
				'link', 
				'removeformat', 
				'indent', 
				'outdent', 
				'fontSize',
				'fontFamily',
				'fontFormat',
				'xhtml'
			]
		}).panelInstance('post');
	break;
	break;
}

/**
 * Assign Member to Project
 */
$('.assignMember').live('click', function(event){

	// Prevent default click event
	event.preventDefault();

	var parentRow = $(this).closest('tr');

	// Get the row id
	var rowId = $(this).attr('row');

	// Get the project id
	var projectId = parseInt( $('.ajaxResults').attr('project_id') );

	var data = {
		'name' 	: $('input[name="user[' + rowId + '][name]"]').val(),
		'leader': $('input[name="user[' + rowId + '][leader]"]').is(':checked') ? 1 : 0
	};

	// Assign member to the project
	$.getJSON(app.ajaxUrl + 'assign-project-member/' + projectId, data, function(response){
		var successful = parseInt(response.successful);

		parentRow.attr('class', 'success').fadeOut(500);
	});
});

/**
 * Remove Member From Project
 */
$('.removeMemberFromProject').live('click', function(event){

	// Prevent default click event
	event.preventDefault();

	var $this = $(this);

	// Capture the user id
	var user_id = $(this).attr('user_id');

	// Capture the project id
	var project_id = $(this).attr('project_id');

	// Prepare the data to be send
	var data = {
		'user_id'	 : user_id
	};

	// Remove member from the project.
	$.getJSON(app.ajaxUrl + 'remove-project-member/' + project_id, data, function(response){

		// Was the removal successful
		var successful = parseInt(response.successful);

		// Remove the container
		if( successful ) $this.closest('.span4').remove();
	});
});

/**
 * Coply quote from another post
 */
$('.quoteReply').on('click', function(event){

	// Prevent Default Click Event
	event.preventDefault();

	// Get the post id
	var post_id = $(this).attr('post_id');

	// Get the name of the poster
	var post_name = $(this).attr('post_name');

	// Capture the quoted source
	var source = $("div[post_id='" + post_id + "']").html();

	// Prepare the new post
	var quotedInsert = '<blockquote>' + source + '<small>'+post_name+'</small></blockquote><br/><br/>';
	
	$('.nicEdit-main').find('br').remove();
	$(quotedInsert).appendTo('.nicEdit-main');

	// Scroll to the bottom of the page.
	var $target = $('html,body'); 
	$target.animate({scrollTop: $target.height()}, 1000);
});

