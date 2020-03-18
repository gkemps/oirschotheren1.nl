/* 
	functies voor Opstelling
*/

function player_positioned(player, pos){
	//Effect.Fade('pos_' + pos, {duration: 3.0, from: 0.0, to: 1.0});
	//Effect.Fade('player_' + player, {duration: 3.0, from: 1.0, to: 0.0});
}

function player_dropped(drag, drop){ 
 	us_pos = drag.id.indexOf('_')
	player_id = drag.id.substring(us_pos + 1, drag.id.length)
	
	us_pos = drop.id.indexOf('_')
	pos_id = drop.id.substring(us_pos + 1, drop.id.length)
	
	if(drag.id.substring(0, 3)=='pos'){
		 new Ajax.Updater('box_' + drag.alt, 'ajax_speelronde.php?c=1', {parameters: {playerid: player_id, 
		 posid: drag.alt, wedid: $F('wedid'), sysid: $F('sysid')}, asynchronous: false})
		 Droppables.add('pic_' + drag.alt,{onDrop: function (drag, drop){player_dropped(drag, drop)}, 
		 accept: ['player_picbox', 'player_picture'], hoverclass: 'player_picbox_hover'});
	}
	else{
		$('nonposplayer_' + player_id).innerHTML = "";
	}
	
	new Ajax.Updater('box_' + pos_id, 'ajax_speelronde.php?c=0', { onComplete: 
		player_positioned(player_id, pos_id), parameters: {playerid:  player_id, 
		posid: pos_id, wedid: $F('wedid'), sysid: $F('sysid')}, 
		asynchronous: false})
		
	new Draggable('posplayer_' + player_id, {revert: true})
	Droppables.add('goal_' + pos_id, 
			{onDrop: function(drag, drop){goal_scored(drag, drop)},
			accept: 'goal', hoverclass: 'goals_hoover'});
	Droppables.add('kaart_' + pos_id, 
			{onDrop: function(drag, drop){kaart_scored(drag, drop)},
			accept: 'kaart', hoverclass: 'kaart_hoover'});
}

function goal_scored (drag, drop){
	us_pos = drop.id.indexOf('_')
	pos_id = drop.id.substring(us_pos + 1, drop.id.length)

	//bepaal aangever
	var vraag = window.prompt('Wie gaf de assist?\n\nGeef een speler met gehele naam, bijv (Bart van Straten)\n of 1 van de volgende opties:\n\n- strafcorner\n- strafbal\n- rebound\n-  individuele actie\n- scrimage\n- strafdoelpunt');
	var aangever;
	
	new Ajax.Request('ajax_speelronde.php?c=5',{parameters: {aangever: vraag, wedid: $F('wedid')}, 
		onSuccess: function(transport){
      var response = transport.responseText || "1";
      aangever = response;}, onFailure: function(){ 
	   	alert('Er ging iets mis, probeer opnieuw!') }, asynchronous: false
  	});
	new Ajax.Updater('box_' + pos_id, 'ajax_speelronde.php?c=2', { parameters: 
		{ posid: pos_id, wedid: $F('wedid'), sysid: $F('sysid'), helft: $F('helft'), 
			aangever: aangever}, asynchronous: false})
	new Ajax.Updater('scores', 'ajax_speelronde.php?c=3', { parameters:
		{wedid: $F('wedid')}, asynchronous: false})
	Droppables.add('tegenstander_' + $F('tegenstander'), {onDrop: function (drag, drop){goal_conceded(drag, drop)},
	 accept: ['goal'], hoverclass: 'goals_hoover'});
	Droppables.add('goal_' + pos_id, 
			{onDrop: function(drag, drop){goal_scored(drag, drop)},
			accept: 'goal', hoverclass: 'goals_hoover'});
	Droppables.add('kaart_' + pos_id, 
			{onDrop: function(drag, drop){kaart_scored(drag, drop)},
			accept: 'kaart', hoverclass: 'kaart_hoover'});
}

function goal_conceded (drag, drop){
	us_pos = drop.id.indexOf('_')
	team_id = drop.id.substring(us_pos + 1, drop.id.length)

	//bepaal aangever
	var aangever = window.prompt('Was het een strafcorner of strafbal?\n\n');

	new Ajax.Updater('scores', 'ajax_speelronde.php?c=7', { parameters:
		{wedid: $F('wedid'), teamid: $F('tegenstander'), helft: $F('helft'), aangever: aangever}, asynchronous: false});
	Droppables.add('tegenstander_' + $F('tegenstander'), {onDrop: function (drag, drop){goal_conceded(drag, drop)},
	 accept: ['goal'], hoverclass: 'goals_hoover'});
}

function kaart_scored(drag, drop){
	us_pos = drop.id.indexOf('_')
	pos_id = drop.id.substring(us_pos + 1, drop.id.length)
	
	kleur = drag.id
	var vraag = window.prompt('Wat was de reden voor de kaart?\n\nGeef een van de volgende opties:\n\n- fysiek\n- praten\n- spel bederf\n-  aanvoerder');
	var reden;
	
	new Ajax.Request('ajax_speelronde.php?c=6',{parameters: {reden: vraag, wedid: $F('wedid')}, 
		onSuccess: function(transport){
      var response = transport.responseText || "1";
      reden = response;}, onFailure: function(){ 
	   	alert('Er ging iets mis, probeer opnieuw!') }, asynchronous: false
  	});
	
	new Ajax.Updater('box_' + pos_id, 'ajax_speelronde.php?c=4', { parameters: 
	{posid: pos_id, wedid: $F('wedid'), kleur: kleur, reden: reden}, asynchronous: false})
	new Ajax.Updater('scores', 'ajax_speelronde.php?c=3', { parameters:
		{wedid: $F('wedid')}, asynchronous: false})
	Droppables.add('tegenstander_' + $F('tegenstander'), {onDrop: function (drag, drop){goal_conceded(drag, drop)},
	 accept: ['goal'], hoverclass: 'goals_hoover'});
	Droppables.add('goal_' + pos_id, 
			{onDrop: function(drag, drop){goal_scored(drag, drop)},
			accept: 'goal', hoverclass: 'goals_hoover'});
	Droppables.add('kaart_' + pos_id, 
			{onDrop: function(drag, drop){kaart_scored(drag, drop)},
			accept: 'kaart', hoverclass: 'kaart_hoover'});
}

function helft_change(elm){
	document.getElementById('helft').value = elm.value;
}

function textarea(id, text) {
     document.getElementById(id).value += text;
}

function ofc(code, wedid, div){
    swfobject.embedSWF("open-flash-chart.swf", div, "500", "300", "9.0.0", "expressInstall.swf", 
    {"data-file":"chart-data.php?c="+code+wedid} );  
}
