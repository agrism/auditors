<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class CompanySetting extends Model
{
	const INVOICE_PRINT_LEFT_MARGIN = 'invoice_print_left_margin_cm';
	const INVOICE_PRINT_RIGHT_MARGIN = 'invoice_print_right_margin_cm';
	const INVOICE_PRINT_TOP_MARGIN = 'invoice_print_top_margin_cm';
	const INVOICE_PRINT_BOTTOM_MARGIN = 'invoice_print_bottom_margin_cm';

	protected $table = 'companies_settings';
	protected $fillable = [
		'company_id', 'variable', 'content',
	];
	public $timestamps = true;

	public static function getConstants() {
		$reflector = new ReflectionClass(__CLASS__);
		return array_diff($reflector->getConstants(),$reflector->getParentClass()->getConstants());
	}
}
