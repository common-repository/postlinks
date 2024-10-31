function lsRefresh($)
{
    $.ajax({
      type: 'POST',
    	url: fsAjax.url,
    	data: {
    		action: 'ls_get_posts',
    		_wpnonce: fsAjax.nonce,
    		id: postID
    	},
      success: function(data, textStatus, XMLHttpRequest)
    	{
        if (data.id)
        {
          var item = $("#ls-postlink-rep-" + data.id);
          if (!data.good)
            item.addClass('bad');
          item.text(data.title);
        }
    	},
    	error: function(MLHttpRequest, textStatus, errorThrown)
      {
      },
      complete: function(XMLHttpRequest, textStatus)
      {
      },
    	dataType: 'json'
    });
  
}

jQuery(document).ready(function($)
{  
  $('#post-body').append('<div id="ls-browser-shade"><div id="ls-browser-box"><br /></div></div>');

  $(".fs-postlink").each(function()
  {
    $(this).hide();
    var style = ' style="min-height: ' + $(this).height() + 'px"';
    var postID = $(this).val();
    var id = ' id="ls-postlink-rep-' + postID + '"';
    $(this).after('<div class="ls-postlink-rep alternate"' + id + style + '></div>');
    
    $.ajax({
      type: 'GET',
    	url: fsAjax.url,
    	data: {
    		action : 'ls_get_title',    
    		_wpnonce : fsAjax.nonce,
    		id: postID
    	},
      success: function(data, textStatus, XMLHttpRequest)
    	{
        if (data.id)
        {
          var item = $("#ls-postlink-rep-" + data.id);
          if (!data.good)
            item.addClass('bad');
          item.text(data.title);
        }
    	},
    	error: function(MLHttpRequest, textStatus, errorThrown)
      {
      },
      complete: function(XMLHttpRequest, textStatus)
      {
      },
    	dataType: 'json'
    });
  });
  
  $('.fs-postlink-add-more').click(function(event)
  {
    $('#ls-browser-shade').show();
    event.preventDefault();
    return true;
  });
  
});