
function drawTree(data) {
	var drawInto = $('.mumbleServerView');

	drawBranch(drawInto, data.tree);
}

function drawBranch(container, tree) {
	var channelList = $('<div class = "channelList" />');
	container.append(channelList);

	$(tree.channels).each(function(i, chan) {
		var channel = $('<p class = "channel" />');
		channel.prepend($('<img src = "resources/images/channel_12.png" alt = "channel icon" />'));
		channel.append($('<span />').addClass('channelTitle').text(chan.name));

		$(channelList).append(channel);

		if ($(chan.subtree).size() > 0) {
			drawBranch(channel, chan.subtree);
		}
	});

	$(tree.users).each(function(i, user) {
		var userHtml = $('<p class = "user" />');
		userHtml.prepend($('<img src = "resources/images/talking_off_12.png" alt = "user icon" />'));
		userHtml.append($('<span />').addClass('username').text(user.username));

		$(channelList).append(userHtml);
	});
}

function refreshTree(serverId) {
}

$(document).ready(function(){
	refreshTree(1);
});
