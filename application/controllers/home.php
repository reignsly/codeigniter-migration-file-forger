<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->dbutil();
	}

	public function index()
	{

		$data = array();

		$data['tables'] = $tables = ($this->db->list_tables());

		$data['result'] = false;

		if($tables){

			foreach ($tables as $key => $tablename) {

				$prefs = array(
		                'tables'      => array($tablename),  // Array of tables to backup.
		                'ignore'      => array(),           // List of tables to omit from the backup
		                'format'      => 'sql',             // gzip, zip, txt
		                'filename'    => 'mybackup.sql',    // File name - NEEDED ONLY WITH ZIP FILES
		                'add_drop'    => false,              // Whether to add DROP TABLE statements to backup file
		                'add_insert'  => false,              // Whether to add INSERT data to backup file
		                'newline'     => "\r\n"               // Newline character used in backup file
		              );

				$backup =& $this->dbutil->backup($prefs); 

				$file = $backup;

				$fields     = '';
				$primarykey = '';
				$otherkey   = '';
				$table      = '';

				// echo 'if(!$this->db->table_exists("'.$tablename.'")){';
				// echo "<br/>";

				$contents = explode("\n",$file);
				foreach ($contents as $key=>$line) {

					$line = trim($line);

					if (preg_match('/CREATE\sTABLE\sIF\sNOT\sEXISTS\s\`(.*?)\`/i',$line,$tablematch))
					{
						$table .= '$this->dbforge->create_table('."'".$tablematch[1]."'".');'.PHP_EOL."<br/>";
						continue;
					}

					if (preg_match('/^`(.*?)`\s(.*?),$/i',$line,$aMatch)) {
						$fields .='$this->dbforge->add_field("'.$aMatch[1].' '.$aMatch[2].'");'.PHP_EOL."<br/>";;
					}

					// lets get our keys and such!
					if (preg_match('/PRIMARY\sKEY\s+\(\`(.*?)\`\)/i',$line,$primary)) {
						$primarykey .= '$this->dbforge->add_key('."'".$primary[1]."'".', TRUE);'.PHP_EOL."<br/>";
					}
					if (preg_match('/KEY\s\`(.*?)\`/i',$line,$indexkey)) {
						$otherkey .= '$this->dbforge->add_key('."'".$indexkey[1]."'".');'.PHP_EOL."<br/>";
					}
				}

				// echo (stripslashes($fields));
				// echo (stripslashes($primarykey));
				// echo (stripslashes($otherkey));
				// echo (stripslashes($table));
				// echo '$this->dbforge->create_table("'.$tablename.'");';
				// echo "<br/>";
				// echo "}";
				// echo "<br/>";
				// echo "<br/>";

			}
		}

		if($this->input->post('show_on_page')){

			if($this->input->post('tables')){

				$tables = $this->input->post('tables');

				if($this->input->post('add_class_name')){
					$data['result'] .= 'class Migration_'.$this->input->post('class_name').' extends CI_Migration {';
					$data['result'] .= "\r\n";
				}

				if($this->input->post('add_up')){

					$data['result'] .= 'public function up(){';
					$data['result'] .= "\r\n";

					foreach ($tables as $key => $tb) {
						
						$rs = $this->dump($tb);

						$data['result'] .= 'if(!$this->db->table_exists("'.$tb.'")){';
						$data['result'] .= "\r\n";

						$data['result'] .= $rs->fields;					
						$data['result'] .= $rs->primarykey;					
						$data['result'] .= $rs->table;										
						$data['result'] .= $rs->otherkey;
						$data['result'] .= '$this->dbforge->create_table("'.$tb.'");';
						$data['result'] .= "\r\n";
						// $data['result'] .= $rs->otherkey;
						$data['result'] .= "}";
						$data['result'] .= "\r\n";
					}

					$data['result'] .= "}";
				}

				if($this->input->post('add_down')){

					$data['result'] .= "\r\n";
					$data['result'] .= 'public function down(){';
					$data['result'] .= "\r\n";

					foreach ($tables as $key => $tb) {
						
						$rs = $this->dump($tb);

						$data['result'] .= 'if($this->db->table_exists("'.$tb.'")){';
						$data['result'] .= "\r\n";
						$data['result'] .= "\t".'$this->dbforge->drop_table("'.$tb.'");';
						$data['result'] .= "\r\n";
						$data['result'] .= "}";
						$data['result'] .= "\r\n";
					}

					$data['result'] .= "}";
				}

				if($this->input->post('add_class_name')){
					$data['result'] .= "\r\n";
					$data['result'] .= "}";
				}
			}
		}
		
		$this->load->view('home', $data);
	}

	private function dump($tablename){
		$prefs = array(
		                'tables'      => array($tablename),  // Array of tables to backup.
		                'ignore'      => array(),           // List of tables to omit from the backup
		                'format'      => 'sql',             // gzip, zip, txt
		                'filename'    => 'mybackup.sql',    // File name - NEEDED ONLY WITH ZIP FILES
		                'add_drop'    => false,              // Whether to add DROP TABLE statements to backup file
		                'add_insert'  => false,              // Whether to add INSERT data to backup file
		                'newline'     => "\r\n"               // Newline character used in backup file
		              );

		$backup =& $this->dbutil->backup($prefs); 
		// var_dump($backup);
		$file = $backup;

		$fields     = '';
		$primarykey = '';
		$otherkey   = '';
		$table      = '';

		$rs = new stdClass();
		$rs->fields = "";
		$rs->primarykey = "";
		$rs->otherkey = "";
		$rs->table = "";

		// echo 'if(!$this->db->table_exists("'.$tablename.'")){';
		// echo "<br/>";

		$contents = explode("\n",$file);
		$fields = array();
		$primarykeys = array();
		$otherkeys = array();

		/* GET KEYS AND SAVE TO AN ARRAY */
		foreach ($contents as $key=>$line) {

			$line = trim($line);

			// get fields
			if (preg_match('/^`(.*?)`\s(.*?),$/i',$line,$aMatch)) {
				$fields[] = $aMatch[1];
			}

			// get primary keys
			if (preg_match('/PRIMARY\sKEY\s+\(\`(.*?)\`\)/i',$line,$primary)) {
				$primarykeys[] = $primary[1];
			}

			//get other keys
			if (preg_match('/KEY\s\`(.*?)\`/i',$line,$indexkey)) {
				$otherkeys[] = $indexkey[1];
			}
		}

		foreach ($contents as $key=>$line) {

			$line = trim($line);

			if (preg_match('/CREATE\sTABLE\sIF\sNOT\sEXISTS\s\`(.*?)\`/i',$line,$tablematch))
			{
				// $rs->table .= '$this->dbforge->create_table('."'".$tablematch[1]."'".');'.PHP_EOL;
				// continue;
			}

			// check fiels
			if (preg_match('/^`(.*?)`\s(.*?),$/i',$line,$aMatch)) {
				if (in_array($aMatch[1], $fields)) {
					$rs->fields .='$this->dbforge->add_field("`'.$aMatch[1].'` '.$aMatch[2].'");'.PHP_EOL;
				}
			}

			// lets get our keys and such!
			if (preg_match('/PRIMARY\sKEY\s+\(\`(.*?)\`\)/i',$line,$primary)) {

				if (in_array($primary[1], $fields)) {
					$rs->primarykey .= '$this->dbforge->add_key('."'`".$primary[1]."`'".', TRUE);'.PHP_EOL;
				}
			}


			//Adding this key turns to be an error
			//update to index key instead
			//sly : update
			if (preg_match('/KEY\s\`(.*?)\`/i',$line,$indexkey)) {
				var_dump($indexkey);
				if (in_array($indexkey[1], $fields)) {
					// var_dump($indexkey[1]);
					// var_dump($fields);
					// die();
					$rs->otherkey .= '$this->dbforge->add_key('."'`".$indexkey[1]."`'".');'.PHP_EOL;
					// $rs->otherkey .= '$sql = "CREATE INDEX `'.$indexkey[1].'` ON `'.$tablename.'`(`'.$indexkey[1].'`)";'.PHP_EOL;
					// $rs->otherkey .= '$this->db->query($sql);'.PHP_EOL;
				}
			}
		}


		return $rs;
		// echo (stripslashes($fields));
		// echo (stripslashes($primarykey));
		// echo (stripslashes($otherkey));
		// echo (stripslashes($table));
		// echo '$this->dbforge->create_table("'.$tablename.'");';
		// echo "<br/>";
		// echo "}";
		// echo "<br/>";
		// echo "<br/>";
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */