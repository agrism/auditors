<?php namespace App\Repositories\Invoice;

interface InvoiceRepository
{

	public function getInvoices(array $params);

	public function getPartners();

	public function getStructuralunits();

	public function getInvoicetypes();

	public function create();


}
