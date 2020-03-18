<?php

require_once('functions/ofc-library/open-flash-chart.php');
require_once("classes/wedstrijd.class.php");
require_once("classes/stand.class.php");

$wedid = substr($_REQUEST['c'], 2);
$case = substr($_REQUEST['c'], 0, 2);           
switch($case){
    
    //onderling resultaat
    case "or":    
           
      $wed = new Wedstrijd($wedid);
      $data = $wed->head2head();     
      //print_r($data);
      
      $thuis_kleur = $wed->homeTeam()->getColor();
      $uit_kleur = $wed->awayTeam()->getColor();

      $thuis = array($data[0]['gw'], $data[0]['gl'], $data[0]['vl']);
      $uit = array($data[1]['gw'], $data[1]['gl'], $data[1]['vl']);   
      $sets = array(array(), array());
      
      $y_axis_max = max($thuis)+2;
      
      for($i=0;$i<count($thuis);$i++){        
        $thuis_bar = new bar_value((integer)$thuis[$i]);
        $uit_bar = new bar_value((integer)$uit[$i]);
        $thuis_bar->set_colour( $thuis_kleur );   
        $uit_bar->set_colour( $uit_kleur );   
        $thuis_bar->set_tooltip( '#val#x' );
        $uit_bar->set_tooltip( '#val#x' );    
        $sets[0][] = $thuis_bar;
        $sets[1][] = $uit_bar; 
      }                  
      
      //$title = new title( "Head 2 Head" );
                     
      $bar1 = new bar_3d();
      $bar1->set_values( $sets[0] );  
      $bar1->set_key($wed->homeTeam()->toString(), 12); 
      $bar1->colour($thuis_kleur);

      $bar2 = new bar_3d();
      $bar2->set_values( $sets[1] );
      $bar2->set_key($wed->awayTeam()->toString(), 12); 
      $bar2->colour($uit_kleur);
      
      $x_axis = new x_axis();
      $x_axis->set_3d(15);
      $x_axis->colour = '#000000';
      $labels = new x_axis_labels();
      $labels->set_labels( array("winst", "gelijk", "verlies") );
      $labels->set_colour("#DAFFDB");
      $x_axis->set_labels($labels); 
      $x_axis->set_grid_colour("#DAFFDB");
      $x_axis->set_tick_height(0);
      
      $y_axis = new y_axis_base();
      $y_axis->set_range(0,$y_axis_max);
      $y_axis->set_colour("#000000");
      $y_axis->set_grid_colour("#DAFFDB");
      
      $chart = new open_flash_chart();
      $chart->set_title( $title );
      $chart->add_element( $bar1 );
      $chart->add_element( $bar2 );
      $chart->set_x_axis( $x_axis );
      $chart->set_y_axis( $y_axis );
      $chart->set_bg_colour("#DAFFDB");
      
      echo $chart->toPrettyString();
    break;
    
    //onderling doelpunten
    case "od":
    
      $wed = new Wedstrijd($wedid);
      $data = $wed->head2head();     
      print_r($data);
      
      $thuis_kleur = $wed->homeTeam()->getColor();
      $uit_kleur = $wed->awayTeam()->getColor();

      $thuis = array($data[0]['voor'],$data[0]['sc'],$data[0]['sb'], $data[0]['1e'], $data[0]['2e']);
      $uit = array($data[1]['voor'], $data[1]['sc'], $data[1]['sb'], $data[1]['1e'], $data[1]['2e']);   
      $sets = array(array(), array());
      
      $y_axis_max = max(max($thuis), max($uit))+2;
      
      for($i=0;$i<count($thuis);$i++){        
        $thuis_bar = new bar_value((integer)$thuis[$i]);
        $uit_bar = new bar_value((integer)$uit[$i]);
        $thuis_bar->set_colour( $thuis_kleur );   
        $uit_bar->set_colour( $uit_kleur );   
        $thuis_bar->set_tooltip( '#val#x' );
        $uit_bar->set_tooltip( '#val#x' );    
        $sets[0][] = $thuis_bar;
        $sets[1][] = $uit_bar; 
      }                  
      
      //$title = new title( "Head 2 Head" );
                     
      $bar1 = new bar_3d();
      $bar1->set_values( $sets[0] );  
      $bar1->set_key($wed->homeTeam()->toString(), 12); 
      $bar1->colour($thuis_kleur);

      $bar2 = new bar_3d();
      $bar2->set_values( $sets[1] );
      $bar2->set_key($wed->awayTeam()->toString(), 12); 
      $bar2->colour($uit_kleur);
      
      $x_axis = new x_axis();
      $x_axis->set_3d(15);
      $x_axis->colour = '#000000';
      $labels = new x_axis_labels();
      $labels->set_labels( array("doelpunten", "strafcorners", "strafballen", "1e helft", "2e helft") );
      $labels->set_colour("#DAFFDB");
      $x_axis->set_labels($labels); 
      $x_axis->set_grid_colour("#DAFFDB");
      $x_axis->set_tick_height(0);
      
      $y_axis = new y_axis_base();
      $y_axis->set_range(0,$y_axis_max);
      $y_axis->set_colour("#000000");
      $y_axis->set_grid_colour("#DAFFDB");
      
      $chart = new open_flash_chart();
      $chart->set_title( $title );
      $chart->add_element( $bar1 );
      $chart->add_element( $bar2 );
      $chart->set_x_axis( $x_axis );
      $chart->set_y_axis( $y_axis );
      $chart->set_bg_colour("#DAFFDB");
      
      echo $chart->toPrettyString();
    
    break;
    
    //case stand
    case "st"   :
        $datum = date("Y-m-d");
        $stand = new Stand($datum);
        $data = $stand->calcGraphData();
        
        $y = new y_axis();
        $y->set_range( 0,42, 3 ); 
        $y->set_grid_colour("#00937A");

        $chart = new open_flash_chart();
        $chart->set_title( new title( 'Verloop dit seizoen' ) );
        $chart->set_bg_colour("#DAFFDB");
        $chart->set_y_axis( $y );    
        foreach($data as $id => $scores){
            $team = new Team($id);
            $labels[] = $team->toString();
            $teamdata = array();
            $sum = 0;
            for($i=0;$i<count($scores);$i++){
                if(isset($scores[$i])){
                    $sum += $scores[$i];
                    $teamdata[] = $sum;
                }
            }        
            if($id==2){
               $default_dot = new solid_dot();
               $default_dot->size(8)->halo_size(3)->colour($team->getColor());         
            }   
            else{
               $default_dot = new dot();            
               $default_dot->size(5)->colour($team->getColor());                
            }
            
            
            $line_dot = new line();
            $line_dot->set_default_dot_style($default_dot);
            $line_dot->set_width( 4 );
            $line_dot->set_colour($team->getColor());
            $line_dot->set_values( $teamdata );
            $line_dot->set_key($team->toString(), 10 );
            $line_dot->set_tooltip($team->toString()." :#val#");
            $chart->add_element( $line_dot );
        }
        echo $chart->toPrettyString();
    break;
}  








