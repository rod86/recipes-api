<?php

namespace App\Libs;

use League\Csv\Reader;
use League\Csv\Writer;

class CSV {

	protected $_file = null;
	protected $_reader = null;
	protected $_headers = [];

	public function __construct($file) {
		$this->_file = $file;
	}

	public function fetchAll($filters = [], $offset = 0, $limit = 0) {
		$this->_reader = Reader::createFromPath($this->_file);
		$this->_headers = $this->_reader->fetchOne();

		if ($offset > 0)
			$this->_reader->setOffset($offset);

		if ($limit > 0)
			$this->_reader->setLimit($limit);

		// Remove headers from results
		$this->_reader->addFilter(function($row) {
			return $row[0] != 'id';
		});

		if ($filters && is_array($filters)) {
			$this->_addFilters($filters);
		}

		return $this->_getResult($this->_reader->fetchAssoc($this->_headers));
	}

	public function fetchBy($column, $value) {
		$this->_reader = Reader::createFromPath($this->_file);
		$this->_headers = $this->_reader->fetchOne();

		$this->_reader->addFilter(function($row) use ($column, $value) {
			return $row[0] != 'id' && $row[$this->_getHeaderIndex($column)] == $value;
		});

		return $this->_getResult($this->_reader->fetchAssoc($this->_headers));
	}

	public function add($data) {
		$row = $this->_buildRow($data);
		$row['id'] = $this->_generateRecipeId();

		$writer = Writer::createFromPath($this->_file);
		return $writer->insertOne(array_values($row));
	}

	public function update($id, $data) {
		//TODO update recipe
	}

	protected function _buildRow($data) {
		$this->_reader = Reader::createFromPath($this->_file);
		$this->_headers = $this->_reader->fetchOne();

		$row = [];
		foreach ($this->_headers as $header) {
			$row[$header] = array_key_exists($header, $data) ? $data[$header] : '';
		}

		return $row;
	}

	protected function _generateRecipeId() {
		$num = 0;

		do {
			$num++;
			$row = $this->fetchBy('id', $num);
		} while((!empty($row)));

		return $num;
	}

	protected function _addFilters($filters) {
		foreach ($filters as $column => $value) {
			$this->_reader->addFilter(function($row) use ($column, $value) {
				return $row[$this->_getHeaderIndex($column)] == $value;
			});
		}
	}

	protected function _getResult($rows) {
		$result = [];
		foreach ($rows as $row)
			$result[] = $row;

		return $result;
	}

	protected function _getHeaderIndex($name) {
		return array_search($name, $this->_headers);
	}
}