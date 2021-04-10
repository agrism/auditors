<?php


namespace App\Exports;


use App\Calendar;
use App\Company;
use App\Employee;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WorkingHours implements FromCollection, WithMapping, WithColumnFormatting, ShouldAutoSize, WithStyles, WithEvents
{
	private $company;
	private $year;
	private $month;
	private $recordCount = 0;
	private $counter = 0;
	private $hours = [
		'Normalas h',
		'Virs h',
		'Nakts h',
		'Komandejumi',
	];
	private $headOffset = 0;
	private $headColsCount = 0;

	private $lastDateOfMonth;
	private $dateRange = [];

	public function __construct(Company $company, string $month, string $year)
	{
		app()->setLocale('lv');

		$this->company = $company;
		$this->year = $year;
		$this->month = $month;

		$this->lastDateOfMonth = Carbon::createFromFormat('Y-m', $year.'-'.$month)->lastOfMonth();

		$range = CarbonPeriod::create((clone $this->lastDateOfMonth)->startOfMonth()->format('Y-m-d'),
			$this->lastDateOfMonth->format('Y-m-d'));
		$calendar = Calendar::get();

		foreach ($range as $date) {

			$isHoliday = false;
			$isWeekend = false;

			if ($calendar->firstWhere('date', $date->format('Y-m-d'))) {
				$isHoliday = true;
			}

			if ($date->isWeekend()) {
				$isWeekend = true;
			}

			$this->dateRange[] = (object) [
				'carbon' => $date,
				'isHoliday' => $isHoliday,
				'isWeekend' => $isWeekend,
				'date' => strval($date->format('d')),
				'regular_hours' => ($isHoliday || $isWeekend) ? 0 : 8,
				'week_Day' => trans('date.weekdays.'.$date->format('l')),
			];
		}

		$count = count($this->dateRange);

		for($i=$count;$i<31;$i++){
			$this->dateRange[] = (object) [];
		}
	}

	public function collection()
	{
		$employees = new Collection();

		// head start
		(function (&$employees) {
			$t = new Employee();
			$t->title = [
				null,
				'Darba laika tabele par: ',
				null,
				$this->month.'/'.$this->year,
			];
			$employees->push($t);
		})($employees);

		(function (&$employees) {
			$c = new Employee();
			$c->name = $this->company->title;
			$employees->push($c);
		})($employees);

		(function (&$employees) {
			$c = new Employee();
			$c->name = 'Reg.: '.$this->company->registration_number;
			$employees->push($c);
		})($employees);

		(function (&$employees) {
			$titles = [
				'',
				'',
				'',
				''
			];

			$counter = 0;

			foreach ($this->dateRange as $date) {
				$counter++;
				$titles[] = $date->week_Day ?? null;
			}

			$title = new Employee();
			$title->title = $titles;

			$employees->push($title);

			$this->headColsCount = count($title->title);

		})($employees);

		$this->headOffset = $employees->count();


		(function (&$employees) {
			$titles = [
				'Nr.',
				'Darbinieks',
				'Amats',
				'Stundas'
			];

			$counter = 0;

			foreach ($this->dateRange as $date) {
				$counter++;
				$titles[] = $date->date ?? null;
			}

			$titles[] = 'Kopā';

			$title = new Employee();
			$title->title = $titles;

			$employees->push($title);

			$this->headColsCount = count($title->title);

		})($employees);

		(function (&$employees) {
			$titles = [
				'',
				'',
				'',
				'Regularas h'
			];

			$rowNumber = $employees->count() + 1;

			$this->addRegularHours($titles, false, $rowNumber);

			$title = new Employee();
			$title->title = $titles;
			$title->hoursLine = true;;

			$employees->push($title);

			$this->headColsCount = count($title->title);

		})($employees);


		// head end

		$counter = 0;

		$this->company->employees->each(function ($employee) use (&$employees, &$counter) {
			foreach (range(1, count($this->hours)) as $key => $range) {

				if ($key !== 0) {
					$titles = [
						'',
						'',
						'',
						$this->hours[$key],
					];
				} else {
					$titles = [
						++$counter,
						$employee->name,
						$employee->role,
						$this->hours[$key],
					];
				}

				$rowNumber = $employees->count() + 1;
				$this->addRegularHours($titles, true, $rowNumber);

				$title = new Employee();
				$title->title = $titles;
				$title->hoursLine = true;;

				$employees->push($title);
			}
		});

		$this->recordCount = $employees->count();

		return $employees;
	}

	private function addFormula(int $rowNumber, int $startColumnIndex, int $endColumnIndex): string
	{
		$startColumnLetter = Coordinate::stringFromColumnIndex($startColumnIndex);
		$endColumnLetter = Coordinate::stringFromColumnIndex($endColumnIndex);
		return '=sum('.$startColumnLetter.''.$rowNumber.':'.$endColumnLetter.''.$rowNumber.')';
	}

	private function addRegularHours(array &$titles, bool $empty = false, $rowNumber)
	{
		$startColumnIndex = count($titles) + 1;

		foreach ($this->dateRange as $date) {
			if ($empty) {
				$titles[] = null;
				continue;
			}
			$titles[] = $date->regular_hours ?? null;
		}

		$endColumnIndex = count($titles);

		$titles[] = $this->addFormula($rowNumber, $startColumnIndex, $endColumnIndex);
	}

//	public function headings(): array
//	{
//
//		$dates = [];
//		foreach (range(1, $this->lastDateOfMonth) as $date){
//			$dates[] = $date;
//		}
//
//		return array_merge([
//			'Numurs',
//			'Vards',
//			'Amats',
//			'Stundas',
//		],$dates);
//	}


	/**
	 * @param  Employee  $employee
	 * @return array
	 */
	public function map($employee): array
	{
//		if(($company->total ?? false) === true){
//			return [
//				null,
//				null,
//				'Kopā:',
//
//			];
//		}

		if (!empty($employee->title)) {
			return $employee->title;
		}


		return [
			$employee->id ? ++$this->counter : null,
			$employee->name ?? null,
			$employee->role ?? null,
			$employee->hours ?? null,
		];
	}


	public function columnFormats(): array
	{
		return [
//			'G' => NumberFormat::FORMAT_NUMBER_00,
//			'H' => NumberFormat::FORMAT_NUMBER_00,
//			'I' => NumberFormat::FORMAT_NUMBER_00,
//			'J' => NumberFormat::FORMAT_NUMBER_00,
		];
	}

	public function styles(Worksheet $sheet)
	{
		$styleArray = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => ['argb' => '000000'],
				],
			]
		];

		$maxColumnLetter = Coordinate::stringFromColumnIndex($this->headColsCount);

		$sheet->getStyle('A'.$this->headOffset.':'.$maxColumnLetter.''.($this->recordCount))->applyFromArray($styleArray);

		$totalRange = 'H'.($this->recordCount + 2).':J'.($this->recordCount + 2);

		$styleFilledColor = [
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'color' => ['argb' => 'adff2f'],
			],
		];

		if ($d = $this->collection()->filter(function ($employee) {
			return !empty($employee->hoursLine);
		})->first()) {

			foreach ($d->title as $key => $value) {
				if ($value === 0) {
					$letter = Coordinate::stringFromColumnIndex($key + 1);
					$range = $letter.''.$this->headOffset.':'.$letter.''.$this->recordCount;
					$sheet->getStyle($range)->applyFromArray($styleFilledColor);
				}
			}
		}

		$topBorderStyle = [
			'borders' => [
				'top' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
					'color' => ['argb' => '000000'],
				],
			],
		];

		$rowsNeedTopBorder = [];

		$counter = 0;
		foreach ($this->collection() as $employee) {
			++$counter;
			if(!empty($employee->title[0])){
				$rowsNeedTopBorder[strval($counter)] = $topBorderStyle;
			}
		}

		$return =  [
			'1' => ['font' => ['bold' => true]],
			'2' => ['font' => ['bold' => true]],
			'3' => ['font' => ['bold' => true]],
			'4' => ['alignment' => ['textRotation' => 90]],
			'5' => ['font' => ['bold' => true]],
//			'E' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]],
		];

		foreach ($rowsNeedTopBorder as $key => $val){
			$return[$key] = $val;
		}

		return $return;
	}


	public function registerEvents(): array
	{
		return [
			BeforeSheet::class => function (BeforeSheet $event) {
				$event->sheet
					->getPageSetup()
					->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
			},
		];
	}
}