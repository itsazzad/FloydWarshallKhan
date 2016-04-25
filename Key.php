<?php
error_reporting(E_ERROR | E_PARSE);
class Key{
	/**
	* Name char
	* @var char
	*/
	private $name=NULL;
	
    /**
    * Surroundings array
    * @var array
    */
	private $surrounding=array(
		'↑'=>NULL,
		'→'=>NULL,
		'↓'=>NULL,
		'←'=>NULL
	);
	
    /**
    * Surroundings array
    * @var array
    */
	private $tranSurrounding=array();

    /**
    * Constructor
    * @param char $name Node Name.
    * @param array $surrounding Node surroundings as an array.
    */
	function __construct($name, $surrounding=NULL) {
		//print "Creating Key:\n";
		$this->create($name, $surrounding);
	}

    /**
    * Create
    * @param char $name Node Name.
    * @param array $surrounding Node surroundings as an array.
    */
	function create($name, $surrounding=NULL){
		$this->name=$name;
		if(isset($surrounding)){
			$this->update($surrounding);
		}
	}
	
    /**
    * Update
    * @param array $surrounding Node surroundings as an array.
    */
	function update($surrounding){
		$this->surrounding=array(
			'↑'=>isset($surrounding['↑'])?$surrounding['↑']:$this->surrounding['↑'],
			'→'=>isset($surrounding['→'])?$surrounding['→']:$this->surrounding['→'],
			'↓'=>isset($surrounding['↓'])?$surrounding['↓']:$this->surrounding['↓'],
			'←'=>isset($surrounding['←'])?$surrounding['←']:$this->surrounding['←']
		);
		$this->tranSurrounding=array_flip($this->surrounding);	
	}
	
    /**
    * getKey
    * @param int $position Key Position in the array.
    * @return int
    */
	function getKey($position=NULL){
		if($position==NULL)
			return $this->surrounding;
		return $this->surrounding[$position];	
	}
	
    /**
    * getTransKey
    * @param int $position Key Position in the array.
    * @return int
    */
	function getTransKey($position=NULL){
		if($position==NULL)
			return $this->tranSurrounding;
		return $this->tranSurrounding[$position];	
	}
}
?>