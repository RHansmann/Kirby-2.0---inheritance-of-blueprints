<?php

class Blueprint extends Obj {

	static public $root		= null;

	public $name 			= null;
	public $file 			= null;
	public $yaml 			= array();

	public $title 			= null;

	public $preview 		= null;
	public $deletable 		= true;
	public $pages 			= null;
	public $files 			= null;
	public $fields 			= array();
	
	private $pages_main		= null;
	private $files_main		= null;
	private $fields_main	= array();
	
	public function __construct($name) {
	
		$this->name = $name;
		$this->file = static::$root . DS . $name . '.php';
		$this->yaml = data::read($this->file, 'yaml');

		// remove the broken first line
		unset($this->yaml[0]);

		// try to get the merge parameter
		$merge 				= a::get($this->yaml, 'merge', null);

		// process sub entry 'begin' of parameter 'merge' (if specified)
		if (!is_null($merge)) {
			if (is_array($merge) && isset($merge['begin'])) {
				if (is_array($merge['begin'])) {
					$arrTemp = $merge['begin'];
				} else {
					// if 'begin' entry has no sub-entries build an array of the single value of 'begin' entry for further processing
					$arrTemp = array();
					$arrTemp[] = $merge['begin'];
				}
				foreach ($arrTemp as $file) {
					$this->mergeFile($file);
				}
			}
		}

		// merge main blueprint file with already processed 'begin' blueprint files
		$this->mergeData($this->yaml);

		// process sub entry 'end' of parameter 'merge' or single value of merge parameter
		if (!is_null($merge)) {
			// only the merge parameter is specified with a single value (file name)
			$arrTemp = array();
			if (!is_array($merge)) {
				$arrTemp[] = $merge;
			} else {
				if (isset($merge['end'])) {
					if (!is_array($merge['end'])) {
						$arrTemp[] = $merge['end'];
					} else {
						$arrTemp = $merge['end'];
					}
				}
			}
			foreach ($arrTemp as $file) {
				$this->mergeFile($file);
			}
		}

		// overwrite title entry anyway by title of main blueprint file
		$this->title 		= a::get($this->yaml, 'title', 'Page');
		
		// Set default values if not yet filled by processing the blueprint file(s)
		if (is_null($this->preview)) {
			$this->preview = 'page';
		}
		if (is_null($this->deletable)) {
			$this->deletable = true;
		}
		if (is_null($this->pages_main)) {
			$this->pages_main = true;
		}
		if (is_null($this->files_main)) {
			$this->files_main = true;
		}

		// Finally prepare the fields for the blueprint form processing
		$this->pages     = new Blueprint\Pages($this->pages_main);
		$this->files     = new Blueprint\Files($this->files_main);	
		$this->fields    = $this->fields_main;
		
	}

	public function fields($page = null) {

		return new Blueprint\Fields($this->fields, $page);

	}

	private function mergeFile($file) {

		$file_merge = static::$root . DS . $file . '.php';
		if (file_exists($file_merge)) {
			// read yaml-data of passed file
			$yaml_merge = data::read($file_merge, 'yaml');
			// remove the broken first line:
			unset($yaml_merge[0]);
			// merge 
			$this->mergeData($yaml_merge);
		}

	}

	private function mergeData($yaml_merge) {

		$preview_merge = a::get($yaml_merge, 'preview', null);
		if (!is_null($preview_merge)) {
			$this->preview = $preview_merge;
		}

		$deletable_merge = a::get($yaml_merge, 'deletable', null);
		if (!is_null($deletable_merge)) {
			$this->deletable = $deletable_merge;
		}

		$pages_merge = a::get($yaml_merge, 'pages', null);
		if (!is_null($pages_merge)) {
			if (is_array($pages_merge)) {
				if (is_array($this->pages_main))  {
					$this->pages_main = array_merge($this->pages_main, $pages_merge);
				} else {
					$this->pages_main = $pages_merge;
				}
			} else {
				$this->pages_main = $pages_merge;
			}
		}

		$files_merge = a::get($yaml_merge, 'files', null);
		if (!is_null($files_merge)) {
			if (is_array($files_merge)) {
				if (is_array($this->files_main))  {
					$this->files_main = array_merge($this->files_main, $file_merge);
				} else {
					$this->files_main = $files_merge;
				}
			} else {
				$this->files_main = $files_merge;
			}
		}

		$fields_merge = a::get($yaml_merge, 'fields', array());
		if (!is_null($fields_merge)) {
			if (is_array($fields_merge)) {
				if (is_array($this->fields_main))  {
					$this->fields_main = array_merge($this->fields_main, $fields_merge);
				} else {
					$this->fields_main = $fields_merge;
				}
			} else {
				$this->fields_main = $fields_merge;
			}
		}
	}

	static public function find($id) {

		if (is_a($id, 'Page')) {
			$name = $id->intendedTemplate();
			$file = static::$root . DS . $name . '.php';
			if (!file_exists($file)) {
				$name = $id->template();
				$file = static::$root . DS . $name . '.php';

				if (!file_exists($file)) {
					$name = 'default';
				}
			}
		} else if (file_exists($id)) {
			$name = f::name($id);
		} else {
			$name = $id;
			$file = static::$root . DS . $name . '.php';
			if (!file_exists($file)) {
				$name = 'default';
			}
		}
		return new static($name);
		
	}

	static public function all() {
	
		$files  = dir::read(static::$root);
		$result = array();
		$home   = site()->homePage()->uid();
		$error  = site()->errorPage()->uid();

		foreach ($files as $file) {

			$name = f::name($file);

			if ($name != 'site' and $name != $home and $name != $error) {
				$result[] = $name;
			}
		}
		return $result;

	}

	public function __toString() {
		return $this->name;
	}

}
