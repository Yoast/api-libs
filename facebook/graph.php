<?php

class Graph {

	private $Facebook;

	public function __construct(Facebook $Facebook) {
		$this->Facebook = $Facebook;
	}


	public function get_apps() {
		$app_list = $this->Facebook->Query('me/applications/developer');



		echo "<pre>";
		print_r($app_list);

	}

}