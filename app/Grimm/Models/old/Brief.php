<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Grimm\Geo\Geo as GeoHelper;

class Brief extends Eloquent {
	protected $table = 'briefe';
	
	protected $fillable = [
		'id',
		'gesehen_12',
		'code',
		'datum',
		'absendeort',
		'absort_ers',
		'empf_ort',
		'absender',
		'empfaenger',
		'sprache',
		'hs',
		'inc',
		'dr_1',
		'dr_2',
		'dr_3',
		'dr_4',
		'dr_5',
		'dr_6',
		'dr_7',
		'faks',
		'konzept',
		'konzept_2',
		'abschrift',
		'abschr_2',
		'abschr_3',
		'abschr_4',
		'kopie',
		'auktkat',
		'auktkat_2',
		'auktkat_3',
		'auktkat_4',
		'erschl_aus',
		'empf_verm',
		'antw_verm',
		'zusatz',
		'zusatz_2',
		'ba',
		'nr_1992',
		'nr_1997',
		'couvert',
		'verz_in',
		'beilage',
		'ausg_notiz',
		'tb_nr',
		'del'
	];
	
	public function unassignedLetters($mode, $limit) {
		$select = (new static())->select($this->table . '.*', 'geo_letter_recv.geo_id as recv_id', 'geo_letter_send.geo_id as send_id')
			->leftJoin('geo_letter_recv', $this->table . '.id', '=', 'geo_letter_recv.letter_id')
			->leftJoin('geo_letter_send', $this->table . '.id', '=', 'geo_letter_send.letter_id');
		
		switch($mode) {
			case 'recv':
				$select->where(function($query) {
						$query->where('geo_letter_recv.geo_id', null);
						$query->where(function($query) {
							$query->where('absort_ers', '!=', '');
							$query->orWhere('absendeort', '!=', '');
						});
					});
				break;
			case 'send':
				$select->where(function($query) {
						$query->where('geo_letter_send.geo_id', null);
						$query->where('empf_ort', '!=', '');
					});
				break;
			case 'both':
				$select->where(function($query) {
						$query->where('geo_letter_recv.geo_id', null);
						$query->where(function($query) {
							$query->where('absort_ers', '!=', '');
							$query->orWhere('absendeort', '!=', '');
						});
					})
					->orWhere(function($query) {
						$query->where('geo_letter_send.geo_id', null);
						$query->where('empf_ort', '!=', '');
					});
				break;
		}
		
		if($limit !== null) {
			$select->limit($take);
		}
		
		return $select;
	}
	
	public static function getUnassignedLetters($mode = 'both', $limit = null) {
		return (new static())->unassignedLetters($mode, $limit);
	}
	
	public function findGeo($type) {
		switch($type) {
			case 'recv':
				return GeoHelper::findIds($this->getAttribute('empf_ort'));
				break;
			
			case 'send':
				return GeoHelper::findIds($this->getAttribute('absort_ers') != '' ? $this->getAttribute('absort_ers') : $this->getAttribute('absendeort'));
				break;
		}
		return array();
	}
}
