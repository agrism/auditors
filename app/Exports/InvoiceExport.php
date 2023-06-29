<?php


namespace App\Exports;


use App\Invoice;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoiceExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithStyles, WithColumnWidths
{
	private $invoices;
	private $recordCount = 0;

	private $invoiceEURWithoutVatAmountTotal = 0;
	private $invoiceEURVatAmountTotal = 0;
	private $invoiceEURTotalAmountTotal = 0;

	public function __construct(\Illuminate\Support\Collection $invoices)
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
			'Samaksas datums',
			'Partneris',
			'PVN numurs',
			'Tips',
			'Strukturvieniba',
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
		if(isset($invoice->amount_total) && isset($invoice->currency_rate) && isset($invoice->invoice_lines)){

			$invoiceEURTotalAmount = ROUND($invoice->amount_total / ($invoice->currency_rate ?: 1), 2);

            $invoiceEURWithoutVatAmount = 0;

			foreach($invoice->invoice_lines as $line){
                $invoiceEURWithoutVatAmount += ($line->quantity * $line->price);
            }

            $invoiceEURWithoutVatAmount = ROUND($invoiceEURWithoutVatAmount / ($invoice->currency_rate ?: 1), 2);

			$invoiceEURVatAmount = $invoiceEURTotalAmount - $invoiceEURWithoutVatAmount;


            $this->invoiceEURWithoutVatAmountTotal += ($invoiceEURWithoutVatAmount ?? 0);
            $this->invoiceEURVatAmountTotal += ($invoiceEURVatAmount ?? 0);
            $this->invoiceEURTotalAmountTotal += ($invoiceEURTotalAmount ?? 0);


            $invoiceEURVatAmount = number_format($invoiceEURVatAmount, 2, '.', '');
            $invoiceEURTotalAmount = number_format($invoiceEURTotalAmount, 2, '.', '');
            $invoiceEURWithoutVatAmount = number_format($invoiceEURWithoutVatAmount, 2, '.', '');

//			$description = !empty($invoice->details_self) ? $invoice->details_self : ($invoice->invoice_lines->first()->title ?? null);
		} else {
            $this->invoiceEURWithoutVatAmountTotal += ($invoiceEURWithoutVatAmount ?? 0);
            $this->invoiceEURVatAmountTotal += ($invoiceEURVatAmount ?? 0);
            $this->invoiceEURTotalAmountTotal += ($invoiceEURTotalAmount ?? 0);
        }

		if(($invoice->total ?? false) === true){
			return [
				null,
				null,
				null,
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

//		dd($invoice);

		return [
			$invoice->number ?? null,
			$invoice->date ?? null,
			$invoice->payment_date ?? null,
			$invoice->partnername ?? null,
			$invoice->partner_vat_number ?? null,
			$invoice->invoicetypename ?? null,
			$invoice->structuralunitname ?? null,
            $invoice->details_self ?? null,
			$invoice->currency_name ?? null,
			number_format($invoice->amount_total ?? null, 2,'.', ''),
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

		$sheet->getStyle('A1:M'.($this->recordCount + 1))->applyFromArray($styleArray);

		$totalRange = 'J'.($this->recordCount + 2).':M'.($this->recordCount + 2);

		$styleFilledColor = [
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'color' => ['argb' => 'adff2f'],
			],
		];

		$sheet->getStyle($totalRange)->applyFromArray($styleArray);
		$sheet->getStyle($totalRange)->applyFromArray($styleFilledColor);

		$headRange = 'A1:M1';
		$sheet->getStyle($headRange)->applyFromArray($styleFilledColor);



		return [
			1 => ['font' => ['bold' => true]],
			'E' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]],

		];
	}

    public function columnWidths(): array
    {
        return [
            'D' => 40,
            'H' => 40,
        ];
    }


}
