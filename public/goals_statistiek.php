<?php
	function percentage($array){
		$total = array_sum($array);
		$result = array();
		foreach($array as $key => $value){
			$perc = round((($value / $total) * 100), 1);
			$key = str_replace("strafcorner", "sc", $key);
			$key = str_replace("strafbal", "sb", $key);
			$key = str_replace("individuele actie", "ind actie", $key);
			$key = str_replace("strafdoelpunt", "(straf dp)", $key);
			$key = str_replace("onbekend", "(onbekend)", $key);
			$result[$key." (".$perc."%)"] = $perc;
		}
		ksort($result);
		return $result;
	}

	require_once("functions/layout.inc.php");
	require_once("classes/GoogChart.class.php");
	pre();
		print "<h2>Statistiek: Goals</h2>";
		$goal = new Goal();
		$goals = $goal->getAllGoals();
		$voor = array();
		$assists = array();
		$assists_tegen = array();
		foreach($goals as $goal){
			$values = $goal->getValues();
			if($values['Team']->getId()=="2"){
				$wedstrijd = new Wedstrijd($values['wedid']);
				if($wedstrijd->homeTeam()->getId()==2)
					$values['Helft'] ==  1 ? $voor["Thuis(1e)"]++ : $voor["Thuis(2e)"]++;
				else
					$values['Helft'] ==  1 ? $voor["Uit(1e)"]++ : $voor["Uit(2e)"]++;
				if(is_string($values['Aangever'])){
					$assists[strtolower($values['Aangever'])]++;
				}
				else
					$assists['assist']++;
			}
			else{
				$wedstrijd = new Wedstrijd($values['wedid']);
				if($wedstrijd->homeTeam()->getId()==2)
					$values['Helft'] ==  1 ? $tegen["Thuis(1e)"]++ : $tegen["Thuis(2e)"]++;
				else
					$values['Helft'] ==  1 ? $tegen["Uit(1e)"]++ : $tegen["Uit(2e)"]++;
				if(is_string($values['Aangever']) and !empty($values['Aangever'])){
					$assists_tegen[strtolower($values['Aangever'])]++;
				}
				else
					$assists_tegen['veldgoal']++;
			}
		}
		
		$goal = new Goal();
		$seizoen = new Seizoen();
		$seizoen->setSeason(date("Y-m-d"));
		$goals = $goal->getAllGoals($seizoen->getId());
		$voor_s = array();
		$tegen_s = array();
		$assists_s = array();
		$assists_tegen_s = array();
		foreach($goals as $goal){
			$values = $goal->getValues();
			if($values['Team']->getId()=="2"){
				$wedstrijd = new Wedstrijd($values['wedid']);
				if($wedstrijd->homeTeam()->getId()==2)
					$values['Helft'] ==  1 ? $voor_s["Thuis(1e)"]++ : $voor_s["Thuis(2e)"]++;
				else
					$values['Helft'] ==  1 ? $voor_s["Uit(1e)"]++ : $voor_s["Uit(2e)"]++;
				if(is_string($values['Aangever'])){
					$assists_s[strtolower($values['Aangever'])]++;
				}
				else
					$assists_s['assist']++;
			}
			else{
				$wedstrijd = new Wedstrijd($values['wedid']);
				if($wedstrijd->homeTeam()->getId()==2)
					$values['Helft'] ==  1 ? $tegen_s["Thuis(1e)"]++ : $tegen_s["Thuis(2e)"]++;
				else
					$values['Helft'] ==  1 ? $tegen_s["Uit(1e)"]++ : $tegen_s["Uit(2e)"]++;
				if(is_string($values['Aangever']) and !empty($values['Aangever'])){
					$assists_tegen_s[strtolower($values['Aangever'])]++;
				}
				else
					$assists_tegen_s['veld goal']++;
			}
		}
		
		$color_ = array(
				'#00937a',
				'#39b097',
				'#4871e5',
				'#6a8bff'
				
			);
		
		$color = array(
				'#00937a',
				'#ffcc33',
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
		$size = array(330, 125);
			
		 print "<table width=\"100%\">";
		 	print "<tr>";
		 		print "<td style=\"padding-bottom:30px\">";
		 			 $chart = new GoogChart();
					 $chart->setChartAttrs( array(
						'type' => 'pie3',
						'title' => 'Doelpunten voor (overall)',
						'tsize' => array("#000000", 16),
						'data' => percentage($voor),
						'size' => $size,
						'color' => array_slice($color_,0, count($voor)),
						));
						echo $chart;
		 		print "</td>";
		 		print "<td style=\"padding-bottom:30px\">";
		 			 $chart = new GoogChart();
					 $chart->setChartAttrs( array(
						'type' => 'pie3',
						'title' => 'Doelpunten voor (seizoen)',
						'tsize' => array("#000000", 16),
						'data' => percentage($voor_s),
						'size' => $size,
						'color' => array_slice($color_,0, count($voor_s)),
						));
						echo $chart;
		 		print "</td>";
		 	print "</tr>";
		 	print "<tr>";
		 		print "<td style=\"padding-bottom:30px\">";
		 			 $chart = new GoogChart();
					 $chart->setChartAttrs( array(
						'type' => 'pie3',
						'title' => 'Doelpunten tegen (overall)',
						'tsize' => array("#000000", 16),
						'data' => percentage($tegen),
						'size' => $size,
						'color' => array_slice($color_,0, count($tegen)),
						));
						echo $chart;
		 		print "</td>";
		 		print "<td style=\"padding-bottom:30px\">";
		 			 $chart = new GoogChart();
					 $chart->setChartAttrs( array(
						'type' => 'pie3',
						'title' => 'Doelpunten tegen (seizoen)',
						'tsize' => array("#000000", 16),
						'data' => percentage($tegen_s),
						'size' => $size,
						'color' => array_slice($color_,0, count($tegen_s)),
						));
						echo $chart;
		 		print "</td>";
		 	print "</tr>";
		 	print "<tr>";
		 		print "<td style=\"padding-bottom:30px\">";
		 			 $chart = new GoogChart();
					 $chart->setChartAttrs( array(
						'type' => 'pie3',
						'title' => 'Ontstaan voor (overall)',
						'tsize' => array("#000000", 16),
						'data' => percentage($assists),
						'size' => $size,
						'color' => array_slice($color,0, count($assists)),
						));
						echo $chart;
		 		print "</td>";
		 		print "<td style=\"padding-bottom:30px\">";
		 			 $chart = new GoogChart();
					 $chart->setChartAttrs( array(
						'type' => 'pie3',
						'title' => 'Ontstaan voor (seizoen)',
						'tsize' => array("#000000", 16),
						'data' => percentage($assists_s),
						'size' => $size,
						'color' => array_slice($color,2, count($assists_s)),
						));
						echo $chart;
		 		print "</td>";
		 	print "</tr>";
		 	print "<tr>";
		 		print "<td style=\"padding-bottom:30px\">";
		 			 $chart = new GoogChart();
					 $chart->setChartAttrs( array(
						'type' => 'pie3',
						'title' => 'Ontstaan tegen (overall)',
						'tsize' => array("#000000", 16),
						'data' => array_reverse(percentage($assists_tegen), true),
						'size' => $size,
						'color' => array_slice($color,2, count($assists_tegen)),
						));
						echo $chart;
		 		print "</td>";
		 		print "<td style=\"padding-bottom:30px\">";
		 			 $chart = new GoogChart();
					 $chart->setChartAttrs( array(
						'type' => 'pie3',
						'title' => 'Ontstaan tegen (seizoen)',
						'tsize' => array("#000000", 16),
						'data' => array_reverse(percentage($assists_tegen_s), true),
						'size' => $size,
						'color' => array_slice($color,2, count($assists_tegen_s)),
						));
						echo $chart;
		 		print "</td>";
		 	print "</tr>";
		 print "</table>";
	post();
?>