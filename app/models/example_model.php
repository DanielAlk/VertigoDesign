<?php
//IT SHOULD BE A TABLE NAMED 'examples' FOR THIS MODEL TO WORK
class example_model extends ActiveBase {
	public $accepts = array('name','age','gender');
}
?>