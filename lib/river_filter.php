<?php
class ElggFilter extends ElggObject {
	protected function initialise_attributes() {
		parent::initialise_attributes();

		$this->attributes['subtype'] = "filter";
	}

	public function __construct($guid = null) {
		parent::__construct($guid);
	}


}
