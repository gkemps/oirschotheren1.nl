<?php
 require_once("functions/layout.inc.php");
 require_once("classes/GoogChart.class.php");
 pre();
 
function nrRounds($data){
	$max = 1;
	foreach($data as $id => $scores){
		if(count($scores)>$max)
			$max = count($scores);
	}	
	return $max;
}
 
 print "<div style=\"width:700px\">";
 	//$datum = "2008-03-01";
 	$datum = date("Y-m-d");
	print "<h2 onclick=\"ofc('st', '".$id."', 'st_chart');\">Huidige stand in de competitie ($datum)</h2>";
	$stand = new Stand($datum);
	$data = $stand->calcGraphData();
	//print_r($data);
	$labels = array();
	$datachart = array();
	$max = 1;
	$cls = array();
	$rounds = nrRounds($data);
	foreach($data as $id => $scores){
	 	if ($id == 2)
	 		array_push($cls, array(4,9,6));
	 	else
	 		array_push($cls, array(1,1,0));
		$team = new Team($id);
		$labels[] = $team->toString();
		$teamdata = array();
		$sum = 0;
		for($i=0;$i<$rounds;$i++){
			if(isset($scores[$i])){
				$sum += $scores[$i];
				$teamdata[] = $sum;
			}
			else
				$teamdata[] = -1;
		}
		if($max < $sum)
			$max = $sum;
		$datachart[$team->toString()] = $teamdata;
	}
	
	$chart = new GoogChart();
	$color = array(
			'#ff0000',
			'#ffff00',
			'#00ff00',
			'#00ffff',
			'#0000ff',
			'#ff00ff',
			'#000000',
			'#ff9933',
			'#006633',
			'#330066',
			'#66cc66',
			'#666666'
		);
		
	$max += $max % 3;
	$points[0] = array(1);
	$points[1] = array(2);
	for($i=0;$i<=$max;$i=$i+3){
		array_push($points[0], $i);
		array_push($points[1], $i);
	}
	
	$chart->setChartAttrs( array(
	'type' => 'line',
	'title' => '',
	'data' => $datachart,
	'size' => array( 700, 400 ),
	'color' => $color,
	'labels' => 'x,y,r',
	'scales' => array(array(0, 0, 22), array(1, 0, $max), array(2, 0, $max)),
	'ranges' => array(array(0, $max)),
	'points' => $points,
	'grid' => array((100/$rounds), (100/$max)*3),
	'linestyles' => $cls
	));
	// Print chart
	echo $chart;

 	$data = array_reverse($stand->calcOirschotGraph(), true);
 	$labels = array();
	$datachart = array();
	$max = 0;
	foreach($data as $id => $scores){
		if(count($scores)>1){
			$seizoen = new Seizoen($id);
			$labels[] = $seizoen->toString();
			$seizoendata = array();
			$sum = 0;
			for($i=0;$i<=22;$i++){
				if(isset($scores[$i])){
					$sum += $scores[$i];
					$seizoendata[] = $sum;
					if($max < $sum)
						$max = $sum;
				}
				else{
					$sum = -1;
					$seizoendata[] = $sum;
				}
			}
			$datachart[$seizoen->toString()] = $seizoendata;
		}
	}
	print "<h2>Seizoenen met elkaar vergeleken</h2>"; 

	$max += $max % 3;
	$points[0] = array(1);
	$points[1] = array(2);
	for($i=0;$i<=$max;$i=$i+3){
		array_push($points[0], $i);
		array_push($points[1], $i);
	}
	$chart->setChartAttrs( array(
	'type' => 'line',
	'title' => '',
	'data' => $datachart,
	'size' => array( 700, 400 ),
	'color' => $color,
	'labels' => 'x,y,r',
	'scales' => array(array(0, 0, 22), array(1, 0, $max), array(2, 0, $max)),
	'ranges' => array(array(0, $max)),
	'points' => $points,
	'grid' => array((100/22), (100/$max)*3),
	'linestyles' => array(array(4, 9, 6))
	));
	// Print chart
	echo $chart;
	print "</div>";
    
    print "<div id=\"st_chart\"></div>";
 
 post();
?>
