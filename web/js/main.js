function HideTweet(id, username, hidden){ 

var obj = {
	id: id,
	username: username,
	hidden: hidden
};

    $.ajax({
    	type: "POST",
        url: '/tweet/hide',
        data: JSON.stringify(obj)
            ,success: function(response) {
                 $('#'+response['id']).toggleClass('classNew');;
            }
    })
    return false;
}
