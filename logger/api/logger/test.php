<?php

namespace logger;

final class test extends base {

	public function write() {

		$this->query(
			"INSERT INTO fdnote(notes, handle) VALUES('%s', '%s')",
			"this is a not",
			"testhandle"
		);

	}

	public function read() {
		$yay = $this->fetchall(
			"SELECT * FROM fdcallbook WHERE csign = 'KG7FZH'"
		);
		print_r($yay);
	}


	public function create_junk_logs(int $logs = 500, $modnote = 20) {

		// where we're going, we're absolutely going to need roads.
		ini_set('memory_limit', -1);

		// get data for fast random
		$bands = $this->fetchall("SELECT * FROM fdband WHERE code != 'none'");
		$zones = $this->fetchall("SELECT * FROM fdzone");
		$class = $this->fetchall("SELECT * FROM fdclass");
		$modes = $this->fetchall("SELECT * FROM fdmode");
		$calls = $this->fetchall("SELECT csign FROM fdcallbook");
		$end   = time();
		$start = $end - 1209600;

		$times = [];
		for ($i = 0 ; $i < $logs ; $i++) $times[] = rand($start, $end);
		sort($times, SORT_NUMERIC);

		$entries = [];
		for ($i = 0 ; $i < $logs ; $i++) {
			$band = $bands[ array_rand($bands) ];

			$entries[] = sprintf(
				"('%s', %d, %d, FROM_UNIXTIME(%d), '%s', '%s', '%s', '%s', %s)",
				$calls[ array_rand($calls) ]["csign"], // callsign
				rand($band["low"], $band["high"]), // frequency
				rand(1,20), // tx
				$times[$i], // time
				$class[ array_rand($class) ]["code"], // class
				$modes[ array_rand($modes) ]["code"], // mode
				$zones[ array_rand($zones) ]["code"], // zone
				'Randmon'.$i, //handle
				($i % $modnote) ? 'NULL' : "'".$this->quote($this->random_phrase())."'"
			);

		}

		foreach (array_chunk($entries, 100) as $part) {
			$this->query(
				"INSERT INTO fdlog(csign, freq, tx, logged, class, mode, zone, handle, notes) VALUES".
				join(",\n", $part)
			);
		}
	
	}

	public function create_junk_notes($notes = 50) {
		$end   = time();
		$start = $end - 1209600;


		$times = [];
		for ($i = 0 ; $i < $notes ; $i++) $times[] = rand($start, $end);
		sort($times, SORT_NUMERIC);

		$entries = [];
		for ($i = 0 ; $i < $notes ; $i++) {

			$entries[] = sprintf(
				"('%s', '%s', FROM_UNIXTIME(%d))",
				$this->quote($this->random_phrase(20)),
				'Ranoted'.$i,
				$times[$i]
			);
		}

		foreach (array_chunk($entries, 10) as $part) {
			$this->query(
				"INSERT INTO fdnote(notes, handle, logged) VALUES".
				join(",\n", $part)
			);
		}
		
	}



	/* so linux, much arch.  wow. */
	protected function random_phrase(int $len = 10): string {
		$string = `shuf -n $len /usr/share/dict/words`;
		$string = preg_replace("/\s/", " ", trim($string));
		return $string;
	}

}
