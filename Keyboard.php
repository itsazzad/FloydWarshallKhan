<?php
require_once("Key.php");
class Keyboard{
    /**
    * cursor char
    * @var cursor
    */
	private $cursor='A';

    /**
    * target int
    * @var target
    */
	private $target;

    /**
    * KeyList array
    * @var keyList
    */
	private $keyList;

    /**
    * keyLinkedList array
    * @var keyLinkedList
    */
	private $keyLinkedList=array();

    /**
    * keyboardMatrix array
    * @var graph
    */
	private $graph=array();
	
    /**
    * nodes array
    * @var nodes
    */
	private $nodes=array();
	
    /**
    * transNodes array
    * @var transNodes
    */
	private $transNodes=array();
	
    /**
    * Predecessor array
    * @var array
    */
    private $pred = array(array());

    /**
    * Constructor
    */
	function __construct() {
		//print "Creating Keyboard:\n";
		$this->createKeyList();
		$this->linkedListCreateLeftRight();
		$this->convertKeyboard2Nodes();
		$this->linkedListUpdateUpDown();
		$this->linkedListSpecialFix();
		$this->convertKeyboard2Graph();
		//print_r($this->keyLinkedList);
	}
	
    /**
    * set_pred.
    */
    function set_pred($pred) {
		$this->pred=$pred;
    }

    /**
    * The actual implementation to get optimal path.
    * @return void
    */
	function findOptimumPath( $fw, $sentence){
		$str_split=str_split($sentence);
		$start=$this->cursor;
		foreach($str_split as $end){
			if($end==" "){
				$end="SPACE";
			}else if($end==chr(8)){
				$end="BS";
			}
			echo "[$start\t$end]:\t";
			$this->target=$this->transNodes[$end];
			$spA = $fw->get_path($this->transNodes[$start],$this->transNodes[$end]);
			//var_export($spA);
			$i=0;
			foreach ($spA as $sp) {
				$i++;
				$sp=array_reverse($sp);
				foreach ($sp as $value) {
					$this->print_path($value);
				}
				if($i!=count($spA))
					echo "\tOR\t";
			}
			echo "↵\n";//&#8629;
			$start=$end;
			//break;
		}
	}
		
    /**
    * Print nodes from a and b.
    * @param ingeger $i Starting node.
    * @param integer $j End node.
    * @return void
    */
    private function print_path($j) {
		$direction=$this->getDirection($this->nodes[$j]);
		//echo "<u>".$this->cursor."</u>";
		print("$direction".$this->nodes[$j]);
		$this->cursor=$this->nodes[$j];
    }

    /**
    * returns the direction from the current cursor key to a surrounding key.
    * @param char $to target key.
    * @return char
    */
	private function getDirection($to){
		$tranSurrounding=$this->keyLinkedList[$this->cursor]->getTransKey();
		return $tranSurrounding[$to];
	}

    /**
    * convertKeyboard2Graph
    */
	public function getGraph(){
		return $this->graph;
	}

    /**
    * convertKeyboard2Graph
    */
	public function getNodes(){
		return $this->nodes;
	}

    /**
    * convertKeyboard2Graph
    */
	public function getTransNodes(){
		return $this->transNodes;
	}

    /**
    * convertKeyboard2Graph
    */
	public function getIndexByKey($key){
		return $this->transNodes[$key];
	}

    /**
    * convertKeyboard2Graph
    */
	private function convertKeyboard2Graph(){
		$count=count($this->nodes);
		$this->transNodes = array_flip($this->nodes);
		foreach($this->nodes as $k => $v){
			//$this->graph[$k]=array_fill(0, $count, INFINITY);
			$this->graph[$k]=array_fill(0, $count, 0);
			$this->graph[$k][$k]=0;
			foreach($this->keyLinkedList[$v]->getKey() as $v2){
				$this->graph[$k][$this->transNodes[$v2]]=1;
			}
		}
	}

    /**
    * convertKeyboard2Graph
    */
	private function initializeGraph(){
		
	}

    /**
    * convertKeyboard2Nodes
    */
	private function convertKeyboard2Nodes(){
		$return = array();
		array_walk_recursive($this->keyList, function($a) use (&$return) { $return[] = $a; });
		$this->nodes = $return;
	}
	
    /**
    * moveCursor.
    * @return void
    */
	public function moveCursor($position){
		$this->cursor=$this->keyLinkedList[$this->cursor]->getKey($position);
	}
	
    /**
    * getCursor.
    * @return char
    */
	public function getCursor(){
		return $this->cursor;
	}
	
    /**
    * printKeyboard
    */
	function printKeyboard(){
			?>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
<?php
	foreach($this->keyList as $keyline => $valueline)
	{
?>
  <tr>
  <?php
  $space=0;
  $bs=0;
  foreach($valueline as $keykey => $valuekey)
  {
	if($valuekey=='SPACE'){
		$space++;
		if($space==8){
		?>
    <td colspan="8" align="center">
        <?php
		}else{
			continue;
		}
	}else if($valuekey=='BS'){
		$bs++;
		if($bs==2){
		?>
    <td colspan="2" align="center">
        <?php
		}else{
			continue;
		}
	}else{
		?>
    <td align="center">
        <?php
	}
	  ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
        <td style="color:gray" align="center"><em><?php echo $this->keyLinkedList[$valuekey]->getKey('↑'); ?></em></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td style="color:gray" align="center"><em><?php echo $this->keyLinkedList[$valuekey]->getKey('←'); ?></em></td>
        <td align="center"><strong style="color:brown;"><?php echo $valuekey; ?></strong><sub><small><u><?php echo $this->transNodes[$valuekey]; ?></u></small></sub></td>
        <td style="color:gray" align="center"><em><?php echo $this->keyLinkedList[$valuekey]->getKey('→'); ?></em></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td style="color:gray" align="center"><em><?php echo $this->keyLinkedList[$valuekey]->getKey('↓'); ?></em></td>
        <td>&nbsp;</td>
      </tr>
    </table>    
    </td>
    <?php
  }
  ?>
  </tr>
  <?php
	}
  ?>
</table>
            <?php
	}

    /**
    * linkedListSpecialFix
    */
	private function linkedListSpecialFix(){
		$this->keyLinkedList['SPACE']->update(array(
			'↑'=>'#',
			'↓'=>'I'
		));
	}

    /**
    * linkedListUpdateUpDown
    */
	private function linkedListUpdateUpDown(){
		$this->updateKeyList();
		$ll=count($this->keyList);
		foreach($this->keyList as $lineKey => $lineValue){
			$top=$lineKey==0?$this->keyList[$ll-1]:$this->keyList[$lineKey-1];
			$cu=$lineValue;
			$do=$lineKey==$ll-1?$this->keyList[0]:$this->keyList[$lineKey+1];
			if($cu!==NULL){
				for($i=0;$i<count($lineValue);$i++){
					$this->keyLinkedList[$cu[$i]]->update(array(
						'↑'=>$top[$i],
						'↓'=>$do[$i]
					));
				}
			}
		}
	}

    /**
    * linkedListCreateLeftRight
    */
	private function linkedListCreateLeftRight(){
		foreach($this->keyList as $lineKey => $lineValue){
			$prev=NULL;
			$curr=NULL;
			$next=NULL;
			$ll=count($lineValue);
			for($i=0;$i<count($lineValue);$i++){
				$prev=$i==0?$lineValue[$ll-1]:$lineValue[$i-1];
				$curr=$lineValue[$i];
				$next=$i==$ll-1?$lineValue[0]:$lineValue[$i+1];
				$key = new Key($curr,array(
					'→'=>$next,
					'←'=>$prev
				));
				$this->keyLinkedList[$curr] = $key;
			}
		}
	}

    /**
    * createKeyList
    */
	private function createKeyList(){
		//echo "Create a keylist with single existence of each key";
		$this->keyList=array(
			range('A', 'Z'),
			range('a', 'z'),
			array_merge(range(0, 9),str_split('!@#$%^&*()'),str_split('?/|\\+-')),
			array_merge(str_split('`~[]{}<>'),array('SPACE'),str_split('.,;:\'"_='),array('BS'))
		);
	}

    /**
    * updateKeyList
    */
	private function updateKeyList(){//Update the keylist as a symmetric/matrix
		$this->keyList[3]=array_merge(str_split('`~[]{}<>'),array_fill(0,8,"SPACE"),str_split('.,;:\'"_='),array('BS','BS'));
	}
	
}
?>