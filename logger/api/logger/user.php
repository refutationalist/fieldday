<?php

namespace logger;


class user extends base {

	public function add(
		string $call,
		int    $tx,
		string $class,
		string $zone,

		int    $freq,
		string $mode,

		string $handle,
		null|string $notes = ""
	): array {


		// test for valid class
		if (!$this->inclasses($class)) return([false, "add: invalid class"]);

		// test for valid zone
		if (!$this->inzones($zone)) return([false, "add: invalid zone"]);

		// test for valid mode
		if (!$this->inmodes($mode)) return([false, "add: invalid mode"]);

		
		// do I test for valid callsign?
		// for now, no.

		// notes!
		$sql_notes = (trim($notes) !== "") ? "'".$this->quote($notes)."'" : 'NULL';

		// handle
		if (trim($handle) === "") 
			return([false, 'add: need a handle']);

		// everything makes sense.   let's go!
		
		if ($this->query(
			"INSERT INTO fdlog SET ".
			"csign = '%s', tx = %d, class = '%s', zone = '%s', freq = %d, mode = '%s', ".
			"handle = '%s', notes = %s",
			$call, $tx, $this->quote($class), $this->quote($zone), $freq, $this->quote($mode),
			$handle, $sql_notes
		)) {
			return([true, 'add: submitted']);
		} else {
			return([false, 'add: db commit error']);
		}

	}

	public function note(
		string $notes, string $handle
	): array {
		if (trim($handle) === "") 
			return([false, 'need a handle']);
		if (trim($notes) === "") 
			return([false, 'note is empty']);

		if ($this->query(
			"INSERT INTO fdnote(handle, notes) VALUES('%s', '%s')",
			$this->quote($handle),
			$this->quote($notes)
		)) {
			return([true, "note: submitted"]);
		} else {
			return([false, "note: db commit error"]);
		}
	}

	public function get(int $lines): array {

		if ($lines != 0) {
			$post = " LIMIT $lines";
		}

		$rows = $this->fetchall("SELECT * FROM fdlogdisplay" . $post);

		if ($rows == false) {
			return([false, "get: db query error"]);
		} else {
			return([true, $rows]);
		}


	}

	public function since(int $logid, int $noteid): array {

		$rows = $this->fetchall(
			"SELECT * FROM fdlogdisplay WHERE (kind = 'log' AND id > %d) OR (kind = 'note' AND id > %d)",
			$logid,
			$noteid
		);

		return( [ true, $rows ] );

	}

	public function dupe(string $call, string $freq, string $mode): array {

		return([ 
			true,
			$this->fetchall(
				"SELECT csign, exch, mode FROM fdlogdisplay ".
				"WHERE csign = '%s' ".
				"AND band = (SELECT code FROM fdband WHERE low < %d AND high > %d LIMIT 1) ".
				"AND mode IN((SELECT code FROM fdmode WHERE cab = (SELECT cab FROM fdmode WHERE code = '%s')))",
				$this->quote($call),
				$freq, $freq,
				$this->quote($mode)
			)
		]);

	}

}