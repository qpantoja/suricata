<?php

/**
 * @author Quetzalcoatl Pantoja Hinojosa
 * @package class
**/

class Gantt
{
	private $diagrama;
	private $definicion = array();
	private $ancho= 800;
	private $alto = 300;
	private $color_fondo = array(189, 156, 59);
	private $grid_color = array(217, 188, 100);
	private $workday_color = array(255,255,255);
	private $title_color = array(255, 255, 255);
	private $title_string = "";
	private $planned = array();
	private $planned_adjusted = array();
	private $real = array();
	private $limit = array();
	private $dependency = array();
	private $milestones = array();
	private $groups = array();
	private $progress = array();
	private $y;
	private $cell;
	private $dependency_planned;

	public function Gantt($definicion) {
		$this->definicion = $definicion;
		//allocate the variables of array definitions to class variables
		foreach ($definicion as $key=>$value) {
			$this->$key = $value;
		}
		$this->definesize();

		//create the image
		$this->diagrama = imagecreatetruecolor($this->ancho,$this->alto);
		//imagealphablending($this->img,true);

		$this->background();
		$this->title();
		$this->grid();
		$this->groups(); // draws groups and phases
		if (is_array($this->dependency_planned)) {
			$this->dependency($this->dependency_planned,'p');
		}
		if (is_array($this->dependency)) {
			$this->dependency($this->dependency);
		}
		if ($this->definicion['today']['data']) {
			$this->today();
		}

		if ($this->definicion['status_report']['data']) {
			$this->last_status_report();
		}

		$this->legend();

		$this->draw();
	}
	
	private function today(){
		$y= $this->definicion['grid']['y']+40;
		$rows = $this->rows();
		$y2 = ($rows*$this->definicion['row']['height'])+$y;
		$x = (($this->definicion['today']['data'] - $this->limit['start'])/(60*60*24))*$this->cell +$this->definicion['grid']['x'];
		//imageline($this->img,$x,$y,$x,$y2,IMG_COLOR_STYLED);
		$this->line_styled($x,$y,$x,$y2,$this->definicion['today']['color'],$this->definicion['today']['alpha'],$this->definicion['today']['pixels']);
	}
	
	private function last_status_report(){
		$y= $this->definicion['grid']['y']+40;
		$rows = $this->rows();


		$y2 = ($rows*$this->definicion['row']['height'])+$y;
		$x = (($this->definicion['status_report']['data'] - $this->limit['start'])/(60*60*24))*$this->cell +$this->definicion['grid']['x'];

		$this->line_styled($x,$y,$x,$y2,$this->definicion['status_report']['color'],$this->definicion['status_report']['alpha'],$this->definicion['status_report']['pixels']);
	}
	
	private function line_styled($x,$y,$x2,$y2,$color,$alpha,$pixels){
		$w  = imagecolorallocatealpha($this->diagrama, 255, 255, 255,100);
		//$red = imagecolorallocate($im, 255, 0, 0);
		$color = $this->color_alocate($color,$alpha);
		for ($i=0;$i<$pixels;$i++){
			$style[] = $color;
		}
		for ($i=0;$i<$pixels;$i++){
			$style[] = $w;
		}

		imagesetstyle($this->diagrama,$style);
		imageline($this->diagrama,$x,$y,$x,$y2,IMG_COLOR_STYLED);
	}
	
	private function groups()	{
		$start_grid = $this->definicion['grid']['x'];
		$this->y = $this->definicion['grid']['y'] + 40;

		foreach ($this->groups['group'] as $cod=>$phases) {

			if ($this->definicion["not_show_groups"] != true) {


				$y = &$this->y;
				$x = (($this->groups['group'][$cod]['start'] - $this->limit['start'])/(60*60*24))*$this->cell +$start_grid;

				$x2 = (($this->groups['group'][$cod]['end']-$this->groups['group'][$cod]['start'])/(60*60*24))*$this->cell +$x;
				//echo "$x : $x2";
				$this->rectangule($x,$y,$x2,$y+6,$this->groups['color'],$this->groups['alpha']);
				$y2 = $y+7;
				$this->polygon(array($x,$y2,$x+10, $y2,$x,$y+15),3,$this->groups['color'],$this->groups['alpha']);
				$this->polygon(array($x2-10,$y2,$x2, $y2,$x2,$y+15),3,$this->groups['color'],$this->groups['alpha']);

				$y2 = $y +$this->definicion['row']['height']/2;


				// title of group
				$this->rectangule(0,$y,$start_grid-1,$y+$this->definicion['row']['height']/2,$this->groups['bg_color']);
				$this->text($this->groups['group'][$cod]['name'],5,$y+$this->definicion['row']['height']/4-6,$this->definicion["group"]['text_color']);

				//border
				$this->border(0,$y,$start_grid,$y2,$this->title_color);
				$this->border($start_grid,$y,$this->ancho-1,$y2,$this->title_color);

				// increase y
				$y += $this->definicion['row']['height']/2;
			}

			//loop group phases
			$this->phases($cod);
			$this->milestones($cod);

		}
	}
	
	private function phases($group){
		$start_grid = $this->definicion['grid']['x'];
		$y = &$this->y;


		//print_r($this->progress);
		foreach ($this->groups['group'][$group]['phase'] as $phase=>$cod) {

			// name of phase
			$this->text($this->planned['phase'][$cod]['name'],15,$y+15,$this->definicion["phase"]['text_color']);

			// planned
			$x = (($this->planned['phase'][$cod]['start'] - $this->limit['start'])/(60*60*24))*$this->cell +$start_grid;
			$x2 = (($this->planned['phase'][$cod]['end']-$this->planned['phase'][$cod]['start'])/(60*60*24))*$this->cell +$x;
			$w1 = $y + $this->definicion['planned']['y'];
			$w2 = $w1 + $this->definicion['planned']['height'];
			$this->definicion['planned']['points'][$cod]['x1'] = $x;
			$this->definicion['planned']['points'][$cod]['x2'] = $x2;
			$this->definicion['planned']['points'][$cod]['y1'] = $w1;
			$this->definicion['planned']['points'][$cod]['y2'] = $w2;
			$this->rectangule($x,$w1,$x2,$w2,$this->planned['color'],$this->planned['alpha']);


			// adjusted
			$t = (($this->planned_adjusted['phase'][$cod]['start'] - $this->limit['start'])/(60*60*24))*$this->cell +$start_grid;
			$t2 = (($this->planned_adjusted['phase'][$cod]['end']-$this->planned_adjusted['phase'][$cod]['start'])/(60*60*24))*$this->cell +$t;
			$w1 = $y + $this->definicion['planned_adjusted']['y'];
			$w2 = $w1 + $this->definicion['planned_adjusted']['height'];
			$this->definicion['planned_adjusted']['points'][$cod]['x1'] = $t;
			$this->definicion['planned_adjusted']['points'][$cod]['x2'] = $t2;
			$this->definicion['planned_adjusted']['points'][$cod]['y1'] = $w1;
			$this->definicion['planned_adjusted']['points'][$cod]['y2'] = $w2;
			$this->rectangule($t,$w1,$t2,$w2,$this->planned_adjusted['color'],$this->planned_adjusted['alpha']);

			//real
			if (isset($this->real['phase'][$cod]['start'])) {


				$z = (($this->real['phase'][$cod]['start'] - $this->limit['start'])/(60*60*24))*$this->cell +$start_grid;
				$z2 = (($this->real['phase'][$cod]['end']-$this->real['phase'][$cod]['start'])/(60*60*24))*$this->cell +$z;
				$w1 = $y + $this->definicion['real']['y'];
				$w2 = $w1 + $this->definicion['real']['height'];
				$this->rectangule($z,$w1,$z2,$w2,$this->real['color'],$this->real['alpha']);
				$this->border($z,$w1,$z2,$w2,$this->definicion['real']['hachured_color']);
				//hachured
				for ($i=$z;$i<($z2-5);$i+=6){
					$this->line($i,$w2,$i+5,$w1,$this->definicion['real']['hachured_color']);
				}
			}
			//progress
			if (isset($this->progress['phase'][$cod]['progress'])) {
				//echo $t."<Br>";
				if ($this->progress['bar_type']=='planned') {
					$this->rectangule($x,$y+$this->progress['y'],(($x2-$x)*($this->progress['phase'][$cod]['progress']/100))+$x,$y+$this->progress['y']+$this->progress['height'],$this->progress['color'],$this->progress['alpha']);
				} else {
				$this->rectangule($t,$y+$this->progress['y'],(($t2-$t)*($this->progress['phase'][$cod]['progress']/100))+$t,$y+$this->progress['y']+$this->progress['height'],$this->progress['color'],$this->progress['alpha']);
				}
			}
			//box
			$x2 = (($this->planned['phase'][$cod]['end']-$this->planned['phase'][$cod]['start'])/(60*60*24))*$this->cell +$start_grid ;
			$y2 = $y +$this->definicion['row']['height'];
			$this->border($start_grid,$y,$this->ancho-1,$y2,$this->title_color);
			$this->border(0,$y,$start_grid,$y2,$this->title_color);
			$y += $this->definicion['row']['height'];
		}
	}
	
	private function milestones($group){
		$y = &$this->y;
		if (is_array($this->groups['group'][$group]['milestone'])) {


			foreach ($this->groups['group'][$group]['milestone'] as $milestone=>$cod) {
				$x = (($this->milestones['milestone'][$cod]['data'] - $this->limit['start'])/(60*60*24))*$this->cell +$this->definicion['grid']['x'];
				// title of group
				$this->rectangule(0,$y,$this->definicion['grid']['x']-1,($y+$this->definicion['row']['height']/2),$this->milestone['title_bg_color']);
				$this->border(0,$y,$this->definicion['grid']['x'],$y+$this->definicion['row']['height']/2,$this->title_color);
				$this->text($this->definicion['milestones']['milestone'][$cod]['title'],15,$y+$this->definicion['row']['height']/4-6,$this->definicion["milestone"]['text_color']);

				//grid box
				$this->border($this->definicion['grid']['x'],$y,$this->ancho-1,$y+$this->definicion['row']['height']/2,$this->title_color);

				//milestone
				$this->polygon(array($x,$y+15,$x+12,$y+15,$x+6,$y),3,$this->milestones['color'],$this->milestones['alpha']);
				$y += $this->definicion['row']['height']/2;
				//echo "$x : $x2";
				//$this->rectangule($x,$y,$x2,$y+6,$this->groups['color']);
			}
		}
	}
	
	private function dependency($dependency,$type='a'){
		imagesetthickness($this->diagrama,2);
		foreach ($dependency as $cod=>$details) {
			$from = $details['phase_from'];
			$to = $details['phase_to'];
			if ($type == 'a') {
				$x[0] =$this->definicion['planned_adjusted']['points'][$from]['x1'];
				$x[1] =$this->definicion['planned_adjusted']['points'][$from]['x2'] ;
				$y[0]=$this->definicion['planned_adjusted']['points'][$from]['y1']+1;
				$y[1]=$this->definicion['planned_adjusted']['points'][$from]['y2'] ;
				$x[2] =$this->definicion['planned_adjusted']['points'][$to]['x1'];
				$x[3] =$this->definicion['planned_adjusted']['points'][$to]['x2'] ;
				$y[2]=$this->definicion['planned_adjusted']['points'][$to]['y1']+1;
				$y[3]=$this->definicion['planned_adjusted']['points'][$to]['y2'] ;
			} elseif ($type == 'p'){
				$x[0] =$this->definicion['planned']['points'][$from]['x1'];
				$x[1] =$this->definicion['planned']['points'][$from]['x2'] ;
				$y[0]=$this->definicion['planned']['points'][$from]['y1']+1;
				$y[1]=$this->definicion['planned']['points'][$from]['y2'] ;
				$x[2] =$this->definicion['planned']['points'][$to]['x1'];
				$x[3] =$this->definicion['planned']['points'][$to]['x2'] ;
				$y[2]=$this->definicion['planned']['points'][$to]['y1']+1;
				$y[3]=$this->definicion['planned']['points'][$to]['y2'] ;
			}
			switch ($details['type']) {
				case END_TO_START:
				//echo 'teste';
				$ydif = 7;

				$this->line($x[1],$y[1],$x[1],$y[1]+$ydif,$this->definicion['dependency_color'][END_TO_START],$definitions['dependency']['alpha']);
				$this->line($x[1],$y[1]+$ydif,$x[2],$y[1]+$ydif,$this->definicion['dependency_color'][END_TO_START],$definitions['dependency']['alpha']);
				$this->line($x[2],$y[1]+$ydif,$x[2],$y[2],$this->definicion['dependency_color'][END_TO_START],$definitions['dependency']['alpha']);

				$this->polygon(array($x[2]-4,$y[2]-4,$x[2]+4,$y[2]-4,$x[2],$y[2]),3,$this->definicion['dependency_color'][END_TO_START],$definitions['dependency']['alpha']);
				break;
				case END_TO_END:
				//echo 'teste';
				$xdif = 10;
				$ydif = 0;
				if ($x[3]>=$x[1]) {


					$this->line($x[1],$y[1],$x[3],$y[1],$this->definicion['dependency_color'][END_TO_END],$definitions['dependency']['alpha']);
					$this->line($x[3],$y[1],$x[3],$y[2],$this->definicion['dependency_color'][END_TO_END],$definitions['dependency']['alpha']);
					$this->polygon(array($x[3]+4,$y[2]-4,$x[3]-4,$y[2]-4,$x[3],$y[2]),3,$this->definicion['dependency_color'][END_TO_END],$definitions['dependency']['alpha']);
				} else {
					$this->line($x[1],$y[1],$x[1],$y[2],$this->definicion['dependency_color'][END_TO_END],$definitions['dependency']['alpha']);
					$this->line($x[1],$y[2],$x[3],$y[2],$this->definicion['dependency_color'][END_TO_END],$definitions['dependency']['alpha']);
					$this->polygon(array($x[3]+4,$y[2]+4,$x[3]+4,$y[2]-4,$x[3],$y[2]),3,$this->definicion['dependency_color'][END_TO_END],$definitions['dependency']['alpha']);
				}
				break;
				case START_TO_START:

				$ydif = 8;


				$this->line($x[0]+1,$y[1],$x[0]+1,$y[1]+$ydif,$this->definicion['dependency_color'][START_TO_START]);
				$this->line($x[0]+1,$y[1]+$ydif,$x[2],$y[1]+$ydif,$this->definicion['dependency_color'][START_TO_START]);
				$this->line($x[2],$y[1]+$ydif,$x[2],$y[2],$this->definicion['dependency_color'][START_TO_START]);


				$this->polygon(array($x[2]-4,$y[2]-4,$x[2]+4,$y[2]-4,$x[2],$y[2]),3,$this->definicion['dependency_color'][START_TO_START]);
				break;
				case START_TO_END:
				//echo 'teste';
				$xdif = 5;

				$ydif = 3;

				$this->line($x[0]+1,$y[1],$x[0]+1,$y[1]+$ydif,$this->definicion['dependency_color'][START_TO_END]);
				$this->line($x[0]+1,$y[1]+$ydif,$x[3],$y[1]+$ydif,$this->definicion['dependency_color'][START_TO_END]);

				$this->line($x[3],$y[1]+$ydif,$x[3],$y[2],$this->definicion['dependency_color'][START_TO_END]);


				$this->polygon(array($x[3]+4,$y[2]-4,$x[3]-4,$y[2]-4,$x[3],$y[2]),3,$this->definicion['dependency_color'][START_TO_END]);
				break;

				default:
				break;
			}
		}
	}
	
	private function line($x1,$y1,$x2,$y2,$color,$alpha = 0) {
		$color = $this->color_alocate($color,$alpha);
		imageline($this->diagrama,$x1,$y1,$x2,$y2,$color);

	}
	
	private function legend(){
		//legend
		$x = 20;
		$x2 = 30;
		$xdiff = 10;
		$ydiff = $this->definicion['legend']['ydiff'];

		$y = $this->alto - $this->definicion['legend']['y'];
		$y_ = $this->definicion['legend']['y_'];
		foreach ($this->planned['phase'] as $cod=>$detail) {
			if ($this->planned['phase'][$cod]['start']) {
				$planned++;
			}
		}
		//$planned = 0;
		if ($planned > 0) {
			//echo "$planned";

			//planned

			$this->rectangule($x,$y+5,$x2,$y+10,$this->planned['color'],$this->planned['alpha']);
			$this->text($this->definicion['planned']['legend'],$x2+$xdiff,$y,$this->definicion["legend"]['text_color']);
			$y +=$ydiff;
			if ($this->alto-$y < $y_) {
				$y = $y = $this->alto - $this->definicion['legend']['y'];
				$x += $this->definicion['legend']['x'];
				$x2 += $this->definicion['legend']['x'];
			}
		}
		// planned_adjusted
		$planned_adjusted = count($this->planned_adjusted['phase']);
		//$planned_adjusted = 0;
		if ($planned_adjusted > 0) {
			$this->rectangule($x,$y+5,$x2,$y+10,$this->planned_adjusted['color'],$this->planned_adjusted['alpha']);
			$this->text($this->definicion['planned_adjusted']['legend'],$x2+$xdiff,$y,$this->definicion["legend"]['text_color']);
			$y +=$ydiff;
			if ($this->alto-$y < $y_) {
				$y = $y = $this->alto - $this->definicion['legend']['y'];
				$x += $this->definicion['legend']['x'];
				$x2 += $this->definicion['legend']['x'];
			}
		}




		//real
		$real = count($this->real['phase']);
		//$real = 0;
		if ($real >0) {
			$this->rectangule($x,$y+5,$x2,$y+10,$this->real['color'],$this->real['alpha']);
			$this->text($this->definicion['real']['legend'],$x2+$xdiff,$y,$this->definicion["legend"]['text_color']);
			for ($i=$x;$i<($x2);$i+=4){
				$this->line($i,$y+10,$i+5,$y+5,$this->definicion['real']['hachured_color']);
			}
			$y +=$ydiff;
			if ($this->alto-$y < $y_) {
				$y = $y = $this->alto - $this->definicion['legend']['y'];
				$x += $this->definicion['legend']['x'];
				$x2 += $this->definicion['legend']['x'];
			}
		}
		// progress
		$progress = count($this->progress['phase']);
		//$progress = 0;
		if ($progress>0) {
			$this->rectangule($x,$y+5,$x2,$y+10,$this->progress['color'],$this->progress['alpha']);
			$this->text($this->definicion['progress']['legend'],$x2+$xdiff,$y,$this->definicion["legend"]['text_color']);
			$y +=$ydiff;
			if ($this->alto-$y < $y_) {
				$y = $y = $this->alto - $this->definicion['legend']['y'];
				$x += $this->definicion['legend']['x'];
				$x2 += $this->definicion['legend']['x'];
			}
		}


		//milestone
		$milestone = count($this->milestones['milestone']);
		//$milestone = 0;
		if ($milestone > 0) {
			$this->polygon(array($x,$y+15,$x+12,$y+15,$x+6,$y),3,$this->milestones['color'],$this->milestones['alpha']);
			$this->text($this->definicion['milestone']['legend'],$x2+$xdiff,$y,$this->definicion["legend"]['text_color']);
			$y +=$ydiff;
			if ($this->alto-$y < $y_) {
				$y = $y = $this->alto - $this->definicion['legend']['y'];
				$x += $this->definicion['legend']['x'];
				$x2 += $this->definicion['legend']['x'];
			}
		}
		//today
		if (isset($this->definicion['today']['data'])) {
			$this->line_styled($x+5,$y+3,$x+5,$y+15,$this->definicion['today']['color'],$this->definicion['today']['alpha'],$this->definicion['today']['pixels']);
			//$this->text($this->definicion['milestone']['legend'],$x2+$xdiff,$y);
			$this->text($this->definicion['today']['legend'],$x2+$xdiff,$y,$this->definicion['legend']['text_color']);
			$y +=$ydiff;
			if ($this->alto-$y < $y_) {
				$y = $y = $this->alto - $this->definicion['legend']['y'];
				$x += $this->definicion['legend']['x'];
				$x2 += $this->definicion['legend']['x'];
			}
		}
		//last status report


		if (isset($this->definicion['status_report']['data'])) {
			$this->line_styled($x+5,$y+3,$x+5,$y+15,$this->definicion['status_report']['color'],$this->definicion['status_report']['alpha'],$this->definicion['status_report']['pixels']);
			$this->text($this->definicion['status_report']['legend'],$x2+$xdiff,$y,$this->definicion["legend"]['text_color']);
		}

	}
	
	private function rows()	{
		$rows = count($this->planned['phase']);
		if ($this->definicion["not_show_groups"] != true){
			$rows += count($this->groups['group'])/2;
		}
		$rows += count($this->milestones['milestone'])/2;
		return $rows;
	}
	
	private function grid()	{
		$months = $this->months($this->limit['start'],$this->limit['end']);
		$n_days = (($this->limit['end']-$this->limit['start'])/(86400))+1;
		$x = $this->definicion['grid']['x'];
		$x1 = $this->definicion['grid']['x'];
		$y= $this->definicion['grid']['y'];
		$rows = $this->rows();
		//echo $rows;
		$y2 = ($rows*$this->definicion['row']['height'])+$y + 40;
		foreach ($months as $month => $startdate) {
			$n_m = next($months);

			$this->border(0,$y,$x,$y+40,$this->title_color);
			if (date("Y",$n_m)> '1969'){ //to bypass a bug in php for windows
				if ($n_m > mktime(0,0,0,2,19,date("Y",$n_m))) {
					$n_m = mktime(0,0,0,date("m",$n_m),date("d",$n_m),date("Y",$n_m));
				}
			}
			if ($n_m < $startdate) {
				$n_m = $this->limit['end']+86400;
			}

			$n_d = ($n_m-$this->limit['start'])/(86400);
			//echo $n_d."<br>";
			if ($n_m >= $this->limit['end']) {
				$x2 = $this->ancho-1;
			} else {
				$x2 = $n_d*$this->cell+$x1;
			}

			//echo $x2."<br>";
			//echo  "<br>";
			$this->rectangule($x,$y,$x2,$y+20,$this->workday_color);
			if ($this->limit['detail']=='m') {
				$ydiff = 15;
			} else{
				$ydiff = 5;
			}

			$this->border($x,$y,$x2,$y+20,$this->title_color);

			if ($this->limit['detail']=='m') {
				$this->rectangule($x,$y+20,$x2,$y2,$this->workday_color);
				$this->border($x,$y,$x2,$y+40,$this->title_color);

			}
			if ($x2 - $x > 45) {
				$this->text($month,$x+($x2-$x)/2-26,$y+$ydiff);
			}
			$x = $x2;
		}

		$x = $this->definicion['grid']['x'];

		//$workdays = $this->workdays($this->limit['start'],$this->limit['end']);
		//print_r($workdays);

		$start = $this->limit['start'];
		$end = $this->limit['end'];
		if ($this->limit['detail']=='m') {
			while( $start <= $end )	{
				$month = date("m",$start);
				$day = date("d",$start);
				$year= date("Y",$start);
				$x2=$x+$this->cell;
				if( date('w', $start ) != 6 && date( 'w', $start) != 0 ){
					//$this->rectangule($x,$y+20,$x2,$y+40,$this->workday_color);
					$this->rectangule($x,$y+41,$x2,$y2,$this->workday_color);
				}else {
					//$this->rectangule($x,$y+20,$x2,$y+40,$this->grid_color);
					$this->rectangule($x,$y+41,$x2,$y2,$this->grid_color);
				}

				//$this->border($x,$y+20,$x2,$y+40,$this->title_color);
				//$day = date("d",$start);
				//$this->text($day,$x+4,$y+23);

				//$this->border($x,$y+41,$x2,$y2,$this->title_color);
				// para corrigir um bug do php que ajusta a data nesse dia
				if ($day == '19' && $month == '2'  ) {
					$start = mktime(0,0,0,2,20,$year);
				} else {
					$start += 86400;
				}


				$x=$x2;
			}
		}
		//day
		if ($this->limit['detail']=='d') {
			while( $start <= $end )	{
				$month = date("m",$start);
				$day = date("d",$start);
				$year= date("Y",$start);
				$x2=$x+$this->cell;
				if( date('w', $start ) != 6 && date( 'w', $start) != 0 ){
					$this->rectangule($x,$y+20,$x2,$y+40,$this->workday_color);
					$this->rectangule($x,$y+41,$x2,$y2,$this->workday_color);
				}else {
					$this->rectangule($x,$y+20,$x2,$y+40,$this->grid_color);
					$this->rectangule($x,$y+41,$x2,$y2,$this->grid_color);
				}

				$this->border($x,$y+20,$x2,$y+40,$this->title_color);
				//$day = date("d",$start);
				$this->text($day,$x+4,$y+23);

				//$this->border($x,$y+41,$x2,$y2,$this->title_color);
				// para corrigir um bug do php que ajusta a data nesse dia
				if ($day == '19' && $month == '2'  ) {
					$start = mktime(0,0,0,2,20,$year);
				} else {
					$start += 86400;
				}


				$x=$x2;
			}
		}
		// week
		if ($this->limit['detail']=='w') {
			while( $start < $end )	{
				$month = date("m",$start);
				$day = date("d",$start);
				$year= date("Y",$start);
				$n_w = (7-date( 'w', $start))*86400+$start;
				if ($n_w > $end || $n_w > $end) {
					$n_w = $end+86400;
				}
				$days = date( 'w', $n_w)-date( 'w', $start);
				if ($days <= 0) {
					$days += 7;
				}
				$x2=$x+$this->cell*$days;
				//$n_w = (7-date( 'w', $start))*86400+$start;



				$this->rectangule($x,$y+20,$x+$this->cell,$y2,$this->grid_color);
				$this->rectangule($x+$this->cell,$y+20,$x2-$this->cell,$y2,$this->workday_color);
				$this->rectangule($x2-$this->cell,$y+20,$x2,$y2,$this->grid_color);
				$this->border($x,$y+20,$x2,$y+40,$this->title_color);
				//$day = date("d",$start);
				$this->text(date( 'd', $start)."-".date( 'd', $n_w-86400),$x+($x2-$x)/2-15,$y+23);

				//$this->border($x,$y+41,$x2,$y2,$this->title_color);
				// para corrigir um bug do php que ajusta a data nesse dia
				$start = $n_w;
				if (date("d",$start) == '19' && date("m",$start) == '2'  ) {
					$start = mktime(0,0,0,2,20,$year);
				}


				$x=$x2;
			}
		}
	}
	
	private function definesize()	{

		if ($this->limit['detail']=='m') {
			$this->cell = $this->limit['cell']['m'];
			$this->limit['start']= mktime(0,0,0,date('m',$this->limit['start']),1,date('Y',$this->limit['start']));

			$this->limit['end']= mktime(0,0,0,date('m',$this->limit['end'])+1,1,date('Y',$this->limit['end']));

		} elseif ($this->limit['detail']=='w') {
			$this->cell = $this->limit['cell']['w'];
			//echo date('w',$this->limit['start']);
			$this->limit['start']= mktime(0,0,0,date('m',$this->limit['start']),date('d',$this->limit['start'])-date('w',$this->limit['start']),date('Y',$this->limit['start']));
			//echo date('w',$this->limit['start']);
			$this->limit['end']= mktime(0,0,0,date('m',$this->limit['end']),date('d',$this->limit['end'])+(6-date('w',$this->limit['end'])),date('Y',$this->limit['end']));



		}elseif ($this->limit['detail']=='d') {
			$this->cell = $this->limit['cell']['d'];
		}

		$n_days = (($this->limit['end']-$this->limit['start'])/(86400));
		$this->ancho = $this->definicion['grid']['x']+ceil($n_days*$this->cell);
		$rows = $this->rows();
		$this->alto = $this->definicion['grid']['y'] + 45+ $this->definicion['legend']['y']  + $rows*$this->definicion['row']['height'];

	}
	
	private function months($start,$end){
		setlocale(LC_TIME,$this->definicion['locale']);
		while( $start <= $end )	{
			$month = strftime("%b %Y",$start);
			$months[$month] = $start;
			$m = date("m",$start);
			$y = date("Y",$start);
			if ($m == '12') {
				$n_m = '1';
				$y = $y +1;
			}else {
				$n_m = $m +1;
			}
			//echo "$n_m / $y <br>";
			$start = mktime(0,0,0,$n_m,1,$y);
			$fev = mktime(0,0,0,2,1,2005);
		}
		//print_r($months);
		//$fev = date("d m Y",$fev);
		//echo "$fev";

		return $months;
	}
	
	private function border($x1,$y1,$x2,$y2,$color){
		imagerectangle($this->diagrama,$x1,$y1,$x2,$y2,$color);
	}
	
	private function rectangule($x1,$y1,$x2,$y2,$color,$alpha = 0){
		$color = $this->color_alocate($color,$alpha);
		imagefilledrectangle($this->diagrama,$x1,$y1,$x2,$y2,$color);
	}
	
	private function title(){
		//kukulcan
		//$color = $this->color_alocate($this->definicion['title_color']);
		$color = $this->color_alocate($this->title_color);
		$this->rectangule(0,0,$this->ancho,$this->definicion['grid']['y'],$this->definicion['titulo_color_fondo']);
		$xdiff = strlen($this->definicion['title_string'])*3;
		if ($this->definicion['title']['ttfont']['file']) {
			$font_size = $this->definicion['title']['ttfont']['size'];
			imagettftext($this->diagrama, $font_size,0, $this->ancho/2-$xdiff,$this->definicion['title_y']+$font_size, $color,$this->definicion['title']['ttfont']['file'],$this->title_string);
		} else{
		imagestring($this->diagrama,$this->definicion['title_font'],$this->ancho/2-$xdiff,$this->definicion['title_y'],$this->title_string,$color);
		}

	}
	
	private function text($string,$x,$y,$color = 0){
		if ($color==0) {
			$color = $this->definicion['text']['color'];
		}
		
		$color = $this->color_alocate($color,0);
        //  print_r($color);
         $font_size = $this->definicion['text']['ttfont']['size'];
         if ($this->definicion['text']['ttfont']['file']){
		
		imagettftext($this->diagrama, $font_size,0, $x,$y+$font_size, $color,$this->definicion['text']['ttfont']['file'],$string);
         } else {
         	imagestring($this->diagrama, $this->definicion['text_font'], $x,$y, $string,$color);
         }
	}
	// alocatte the color for background
	private function background(){
		$bg = imagecolorallocate($this->diagrama,$this->color_fondo[0],$this->color_fondo[1],$this->color_fondo[2]);
		imagefill($this->diagrama,0,0,$bg);
	}
	
	private function color_alocate($color,$alpha = 40){
		return imagecolorallocatealpha($this->diagrama,$color[0],$color[1],$color[2],$alpha);
	}
	
	private function polygon($points, $n_points, $color,$alpha=0){
		$color = $this->color_alocate($color,$alpha);
		imagefilledpolygon($this->diagrama,$points,$n_points,$color);
	}
	//generate the image
	private function draw($image_type= 'png')	{

		//echo  "ok, chegou até aqui";
		if ($this->definicion['image']['type']) {
			$image_type = $this->definicion['image']['type'];
		}
		if ($this->definicion['image']['filename']) {
			$filename = $this->definicion['image']['filename'];
		}
		if ($this->definicion['image']['jpg_quality']) {
			$jpg_quality = $this->definicion['image']['jpg_quality'];
		} else {
			$jpg_quality = 100;
		}
		if ($this->definicion['image']['wbmp_foreground']) {
			$foreground = $this->color_alocate($this->definicion['image']['wbmp_foreground']);
		} else {
			$foreground = null;
		}

		switch ($image_type) {
			case 'png':
			if (function_exists("imagepng")) {
				header("Content-type: image/png");
				if ($filename) {
					imagepng($this->diagrama,$filename);
				} else {
					imagepng($this->diagrama);
				}

			}
			break;
			case 'gif':
			if (function_exists("imagegif")) {
				header("Content-type: image/gif");
				if ($filename) {
					imagegif($this->diagrama,$filename);
				} else {
					imagegif($this->diagrama);
				}
				//imagegif($this->img,$filename);
			}
			break;
			case 'jpg':
			if (function_exists("imagejpeg")) {
				header("Content-type: image/jpeg");
				imagejpeg($this->diagrama,$filename, $jpg_quality);
			}
			break;
			case 'wbmp':
			if (function_exists("imagewbmp")) {
				header("Content-type: image/vnd.wap.wbmp");
				if ($filename) {
					imagewbmp($this->diagrama,$filename,$foreground);
				} else {
					imagewbmp($this->diagrama,'',$foreground);
				}

			}
			break;
			default:
			die("Este servidor PHP no soporta imagenes tipo $image_type");
			break;
		}

		imagepng($this->diagrama);
		imagedestroy($this->diagrama);
	}

}

?>