<?php
define('INFINITY', 111);
class FloydWarshallKhan {

    /**
    * Distances array
    * @var array
    */
    private $dist = array(array());
    /**
    * Predecessor array
    * @var array
    */
    private $pred = array(array());
    /**
    * Predecessor temp array
    * @var array
    */
    private $pred_i = array(array());
    /**
    * Weights array
    * @var array
    */
    private $weights;
    /**
    * Number of nodes
    * @var integer
    */
    private $nodes;
    /**
    * Node names array
    * @var array
    */
    private $nodenames;
    /**
    * Temporary table for various stuff.
    * @var array
    */
    private $tmp = array();

    /**
    * Temporary path(s).
    * @var array
    */
    private $tmppaths = array();

    /**
    * Constructor
    * @param array $graph Graph matrice.
    * @param array $nodenames Node names as an array.
    */
    public function __construct($graph, $nodenames='') {

        $this->weights = $graph;
        $this->nodes   = count($this->weights);
        if ( ! empty($nodenames) && $this->nodes == count($nodenames) ) {
            $this->nodenames = $nodenames;
        }
        $this->__floydwarshallkhan();

    }

    /**
    * The actual PHP implementation of Floyd-Warshall algorithm.
    * @return void
    */
    private function __floydwarshallkhan () {

        // Initialization
        for ( $i = 0; $i < $this->nodes; $i++ ) {
            for ( $j = 0; $j < $this->nodes; $j++ ) {
                if ( $i == $j ) {
                    $this->dist[$i][$j] = 0;
                } else if ( $this->weights[$i][$j] > 0 ) {
                    $this->dist[$i][$j] = $this->weights[$i][$j];
                } else {
                    $this->dist[$i][$j] = INFINITY;
                }
                $this->pred[$i][$j] = $i;
            }
        }

        // Algorithm

        for ( $k = 0; $k < $this->nodes; $k++ ) {
            for ( $i = 0; $i < $this->nodes; $i++ ) {
                for ( $j = 0; $j < $this->nodes; $j++ ) {
                    if ($this->dist[$i][$j] > ($this->dist[$i][$k] + $this->dist[$k][$j])) {
                        $this->dist[$i][$j] = $this->dist[$i][$k] + $this->dist[$k][$j];
                        $this->pred[$i][$j] = $this->pred[$k][$j];
                    }else if ($this->dist[$i][$j] == ($this->dist[$i][$k] + $this->dist[$k][$j])) {//Extension to the original FloydWarshall
						if($this->dist[$i][$j]==INFINITY || $this->dist[$i][$k]==INFINITY || $this->dist[$k][$j]==INFINITY){
						}else{
							$this->pred[$i][$j] = array_diff(array_unique(array_merge((array)$this->pred[$i][$j], (array)$this->pred[$k][$j])), array($j));
						}//
					}//
                }
            }
        }//
    }

    /**
    * Private method to get the path.
    *
    * Get graph path from predecessor matrice.
    * @param integer $i
    * @param integer $j
    * @return void
    */
    private function __get_path($i, $j) {//Extension to the original FloydWarshall
		array_push($this->tmp, $j);//
        if ( $i != $j ) {
			if(is_array($this->pred_i[$j])){
				foreach($this->pred_i[$j] as $_j){
					$this->__get_path($i, $_j);
				}
			}else{
				$this->__get_path($i, $this->pred_i[$j]);
			}
        }else{
			array_push($this->tmppaths, $this->tmp);//Final
			$this->tmp = array();
		}
		return;
    }

    /**
    * Public function to access get path information.
    *
    * @param ingeger $i Starting node.
    * @param integer $j End node.
    * @return array Return array of nodes.
    */
    public function get_path($i, $j) {//Extension to the original FloydWarshall
        $this->pred_i = $this->pred[$i];
		
		$this->tmp = array();
		$this->tmppaths = array();
		$this->__get_path($i, $j);
		for($i=1;$i<count($this->tmppaths);$i++){
			$merge=array();
			for($j=0;$j<(count($this->tmppaths[$i-1])-count($this->tmppaths[$i]));$j++){
				$merge[]=$this->tmppaths[$i-1][$j];
			}
			$this->tmppaths[$i]=array_merge($merge, $this->tmppaths[$i]);
		}
		//var_export($this->tmppaths);
        return $this->tmppaths;
    }
    /**
    * convertKeyboard2Nodes
    */
	private function flattenArray($array){
		$return = array();
		array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
		return $return;
	}

    /**
    * Print nodes from a and b.
    * @param ingeger $i Starting node.
    * @param integer $j End node.
    * @return void
    */
    public function print_path($i, $j) {//INCOMPLETE
		//TODO: for the extension
		echo "Its an incomplete method still now.";
        if ( $i != $j ) {
			echo "[$i:$j]";
            $this->print_path($i, $this->pred[$i][$j]);
        }

        if (! empty($this->nodenames) ) {
            print($this->nodenames[$j]) . ' ';
        } else {
            print($j) . ' ';
        }

    }

    /**
    * Get total cost (distance) between point a to b.
    *
    * @param integer $i
    * @param ingeger $j
    * @return array Returns an array of costs.
    */
    public function get_distance($i, $j) {
        return $this->dist[$i][$j];
    }

    /************************************************************
    ***                    DEBUG FUNCTIONS                    ***
    ***                    - print_graph                      ***
    ***                    - print_dist                       ***
    ***                    - print_pred                       ***
    *************************************************************/

    /**
    * Print out the original Graph matrice.
    * @return void
    */
    public function print_graph () {


        if ( empty($_SERVER['argv']) ) {
            echo '<strong>Graph</strong><br />';
            echo '<table border="1" cellpadding="4">';
            if (! empty($this->nodenames) ) {
                echo '<tr>';
                echo '<td>&nbsp;</td>';
                for ($n = 0; $n < $this->nodes; $n++) {
                    echo '<td width="15" align="center"><strong>' .
                         "[$n]".$this->nodenames[$n] .
                        '</strong></td>';
                }
            }
            echo '</tr>';
            for ($i = 0; $i < $this->nodes; $i++) {
                echo '<tr>';
                if (! empty($this->nodenames) ) {
                    echo '<td width="15" align="center"><strong>' .
                         "[$i]".$this->nodenames[$i] .
                        '</strong></td>';
                }
                for ($j = 0; $j < $this->nodes; $j++) {
                    echo '<td width="15" align="center">' .
                     $this->weights[$i][$j] . '</td>';
                }   
                echo '</tr>';
            }
            echo '</table><br />';

        } else {

        }
    }

    /**
    * Print out distances matrice.
    * @return void
    */
    public function print_dist () {

        if ( empty($_SERVER['argv']) ) {
            echo '<strong>Distances</strong><br />';
            echo '<table border="1" cellpadding="4">';
            if (! empty($this->nodenames) ) {
                echo '<tr>';
                echo '<td>&nbsp;</td>';
                for ($n = 0; $n < $this->nodes; $n++) {
                    echo '<td width="15" align="center"><strong>' .
                        $this->nodenames[$n] .
                        '</strong></td>';
                }
            }
            echo '</tr>';
            for ($i = 0; $i < $this->nodes; $i++) {
                echo '<tr>';
                if (! empty($this->nodenames) ) {
                    echo '<td width="15" align="center"><strong>' .
                         $this->nodenames[$i] .
                        '</strong></td>';
                }
                for ($j = 0; $j < $this->nodes; $j++) {
                    echo '<td width="15" align="center">' .
                         $this->dist[$i][$j] . '</td>';
                }
                echo '</tr>';
            }
            echo '</table><br />';
        } else {
            echo "cmd line not yet completed!\n";
        }

    }
	
    /**
    * get_pred.
    */
    function get_pred() {
		return $this->pred;
    }
	
    /**
    * print_r Pred.
    */
    function print_r_pred() {
		print_r($this->pred);
    }

    /**
    * Print out predecessors matrice.
    * @return void
    */
    public function print_pred () {

        if ( empty($_SERVER['argv']) ) {
            echo '<strong>Predecessors</strong><br />';
            echo '<table border="1" cellpadding="4">';
            if (! empty($this->nodenames) ) {
                echo '<tr>';
                echo '<td>&nbsp;</td>';
                for ($n = 0; $n < $this->nodes; $n++) {
                    echo '<td width="15" align="center"><strong>' .
                         $this->nodenames[$n] .
                        '</strong></td>';
                }
            }
            echo '</tr>';
            for ($i = 0; $i < $this->nodes; $i++) {
                echo '<tr>';
                if (! empty($this->nodenames) ) {
                    echo '<td width="15" align="center"><strong>' .
                         $this->nodenames[$i] .
                        '</strong></td>';
                }
                for ($j = 0; $j < $this->nodes; $j++) {
                    echo '<td width="15" align="center">';
                    if(is_array($this->pred[$i][$j])){
						//print_r($this->pred[$i][$j]);
						foreach($this->pred[$i][$j] as $p){
							if(is_array($p)){
								echo "<pre>";
								print_r($p);
								echo "</pre>";
							}else{
								echo $this->nodenames[$p];
							}
						}
					}else{
						echo $this->nodenames[$this->pred[$i][$j]];
					}
					echo '</td>';
                }
                echo '</tr>';
            }
            echo '</table><br />';
        } else {
            echo "cmd line not yet completed!\n";
        }

    }

} // End of class
?>
