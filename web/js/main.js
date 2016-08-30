function HideTweet(url){ 

    $.ajax({
    	type: "POST",
        url: url,
        success: function(response) {
        	$('#'+response['id']+'_div').toggleClass('ShowOrHidden');
        	$('#'+response['id']+'_a').toggleClass('Hidden');

        	var value_hidden = "Hide";
        	if($('#'+response['id']+'_a').hasClass('Hidden')){
    			value_hidden = "Show";
			}
			$('#'+response['id']+'_a').text(value_hidden);
        }
    });
    return false;
}
