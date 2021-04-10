<?php


namespace App\Exports;


use App\Invoice;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoiceExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithStyles
{
	private $invoices;
	private $recordCount = 0;

	private $invoiceEURWithoutVatAmountTotal = 0;
	private $invoiceEURVatAmountTotal = 0;
	private $invoiceEURTotalAmountTotal = 0;

	public function __construct(Collection $invoices)
	{
		$this->invoices = $invoices;
		$this->recordCount = $this->invoices->count();
	}

	public function collection()
	{
		$this->invoices->add((object)[
			'total' => true,
		]);

//		dd($this->invoices);
		return $this->invoices;
	}

	public function headings(): array
	{
		return [
			'Numurs',
			'Datums',
			'Partneris',
			'Tips',
			'Apraksts',
			'Valuta',
			'Summa valuta',
			'Summa bez PVN, EUR',
			'Summa PVN, EUR',
			'Summa ar PVN, EUR',
		];
	}


	/**
	 * @param  Invoice  $invoice
	 * @return array
	 */
	public function map($invoice): array
	{
		if(isset($invoice->amount_total) && isset($invoice->currency_rate) && isset($invoice->invoiceLines)){
			$invoiceEURTotalAmount = ROUND($invoice->amount_total / $invoice->currency_rate, 2);

			$invoiceEURWithoutVatAmount = ROUND($invoice->invoiceLines->sum(function ($line) {
					return $line->quantity * $line->price;
				}) / $invoice->currency_rate, 2);

			$invoiceEURVatAmount = $invoiceEURTotalAmount - $invoiceEURWithoutVatAmount;

			$description = !empty($invoice->details_self) ? $invoice->details_self : ($invoice->invoiceLines->first()->title ?? null);
		}

		$this->invoiceEURWithoutVatAmountTotal += ($invoiceEURWithoutVatAmount ?? 0);
		$this->invoiceEURVatAmountTotal += ($invoiceEURVatAmount ?? 0);
		$this->invoiceEURTotalAmountTotal += ($invoiceEURTotalAmount ?? 0);

		if(($invoice->total ?? false) === true){
			return [
				null,
				null,
				null,
				null,
				null,
				null,
				'Kopā:',
				$this->invoiceEURWithoutVatAmountTotal,
				$this->invoiceEURVatAmountTotal,
				$this->invoiceEURTotalAmountTotal,
			];
		}

		return [
			$invoice->number ?? null,
			$invoice->date ?? null,
			$invoice->partner->name ?? null,
			$invoice->invoiceType->title ?? null,
			$description ?? null,
			$invoice->currency_name ?? null,
			$invoice->amount_total ?? null,
			$invoiceEURWithoutVatAmount ?? null,
			$invoiceEURVatAmount ?? null,
			$invoiceEURTotalAmount ?? null,
		];
	}


	public function columnFormats(): array
	{
		return [
			'G' => NumberFormat::FORMAT_NUMBER_00,
			'H' => NumberFormat::FORMAT_NUMBER_00,
			'I' => NumberFormat::FORMAT_NUMBER_00,
			'J' => NumberFormat::FORMAT_NUMBER_00,
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

		$sheet->getStyle('A1:J'.($this->recordCount + 1))->applyFromArray($styleArray);

		$totalRange = 'H'.($this->recordCount + 2).':J'.($this->recordCount + 2);

		$styleFilledColor = [
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'color' => ['argb' => 'adff2f'],
			],
		];

		$sheet->getStyle($totalRange)->applyFromArray($styleArray);
		$sheet->getStyle($totalRange)->applyFromArray($styleFilledColor);

		$headRange = 'A1:J1';
		$sheet->getStyle($headRange)->applyFromArray($styleFilledColor);



		return [
			1 => ['font' => ['bold' => true]],
			'E' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]],

		];
	}


}