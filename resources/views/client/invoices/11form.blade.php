@if (count($errors) > 0)
<div class="alert alert-danger">
	<ul>
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif

<div class="form-group">
	{!! Form::label('date', 'Date', ['class'=>'col-sm-2 control-label']) !!}
	<div class="col-sm-3">
		{!! Form::text('date', isset($invoice) ? $invoice['date'] : null , ['class'=>'form-control', 'placeholder'=>'Input date', 'id'=>'dp1', 'readonly'] ) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('payment_date', 'Payment Date', ['class'=>'col-sm-2 control-label']) !!}
	<div class="col-sm-3">
		{!! Form::text('payment_date', isset($invoice) ? $invoice['payment_date'] : null , ['class'=>'form-control', 'placeholder'=>'Input payment date', 'id'=>'dp2', 'readonly'] ) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('number', 'No', ['class'=>'col-sm-2 control-label']) !!}
	<div class="col-sm-3">
		{!! Form::text('number', isset($invoice) ? $invoice['number'] : null , ['class'=>'form-control', 'placeholder'=>'Input No.'] ) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('partner_id', 'Partner', ['class'=>'col-sm-2 control-label']) !!} 
	<div class="col-sm-10">
		{!! Form::select('partner_id', $partners ,isset($invoice) ? $invoice['partner_id'] : null , ['class'=>'form-control', 'placeholder'=>'Select partner '] ) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('bank_id', 'Optional Payment receiver', ['class'=>'col-sm-2 control-label']) !!}
	<div class="col-sm-10">
		{!! Form::select('bank_id', $bank ,isset($selectedBank) ? $selectedBank['id'] : null , ['class'=>'form-control', 'placeholder'=>'Select optional payment receiver'] ) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('currency_id', 'Currency', ['class'=>'col-sm-2 control-label']) !!}
	<div class="col-sm-10">
		{!! Form::select('currency_id', $currencies ,isset($invoice) ? $invoice['currency_id'] : null , ['class'=>'form-control', 'placeholder'=>'Select currency'] ) !!}
	</div>
</div>

{{-- foreach line dtaa from db --}}
@if( isset($invoice) )
@foreach($invoice->invoiceLines as $line)
{!! Form::hidden('line_id[]', $line->id) !!}
<div class="form-group inline invoice-line">
	<div class="col-sm-3">
		{!! Form::text('title[]', isset($line) ? $line['title'] : null , ['class'=>'form-control input-sm line_title line-1', 'placeholder'=>'title'] ) !!}
	</div>
	<div class="col-sm-2">
		{!! Form::text('quantity[]', isset($line) ? $line['quantity'] : null , ['class'=>'form-control input-sm line_quantity line-1', 'placeholder'=>'quantity'] ) !!}
	</div>

	<div class="col-sm-2">
		{!! Form::text('price[]', isset($line) ? $line['price'] : null , ['class'=>'form-control input-sm line_price line-1', 'placeholder'=>'price'] ) !!}
	</div>
	<div class="col-sm-2 ">
		{!! Form::text('total[]',  isset($line) ? $line['price'] * $line['quantity']  : null , ['class'=>'form-control input-sm line_total line-1', 'placeholder'=>'total', 'readonly'] ) !!}
	</div>

	<div class="col-sm-2">
		{!! Form::select('vat_id[]', $vats->pluck('name', 'id') ,isset($line) ? $line['vat_id'] : null , ['class'=>'form-control input-sm line_vat_id line-1', 'placeholder'=>'vat'] ) !!}

	</div>
	<div class="btn btn-xs btn-danger fa fa-remove remove-line"></div>

</div>

@endforeach
@endif
{{-- end foreach line data from db  --}}


{{--  empty line starts --}}
{!! Form::hidden('line_id[]', null) !!}
<div class="form-group inline invoice-line" id="line-empty-div">
	<div class="col-sm-3 ">
		{!! Form::text('title[]', null , ['class'=>'form-control input-sm line_title line-1', 'placeholder'=>'title'] ) !!}
	</div>
	<div class="col-sm-2"> 
		{!! Form::text('quantity[]', null , ['class'=>'form-control input-sm line_quantity line-1', 'placeholder'=>'quantity'] ) !!}
	</div>

	<div class="col-sm-2">
		{!! Form::text('price[]', null , ['class'=>'form-control input-sm line_price line-1', 'placeholder'=>'price'] ) !!}
	</div>

	<div class="col-sm-2 ">
		{!! Form::text('total[]', null , ['class'=>'form-control input-sm line_total line-1', 'placeholder'=>'total', 'readonly'] ) !!}
	</div>

	<div class="col-sm-2">
		{!! Form::select('vat_id[]', $vats->pluck('name', 'id') ,null , ['class'=>'form-control input-sm line_vat_id line-1', 'placeholder'=>'vat'] ) !!}

	</div>
	<div class="btn btn-xs btn-danger fa fa-remove remove-line"></div>

</div>
<hr>
<div class="btn btn-info fa-plus fa" id="addLine"></div>
{{--  empty line ends --}}


{{-- here starts subTotals, tax, total- by tax rates! --}}

{{-- rate 21% --}}

@foreach($vats as $vat)
<div class="hide">
	<div class="form-group">
		{!! Form::label('invoiceBeforeTaxTotal_'.$vat->id, 'Total before tax ('.$vat->name.'):', ['class'=>'col-sm-9 control-label']) !!}
		<div class="col-sm-2">
			{!! Form::text('invoiceBeforeTaxTotal_'.$vat->id, null , ['class'=>'form-control', 'placeholder'=>'', 'id'=>'invoiceBeforeTaxTotal_'.$vat->id, 'readonly'] ) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('invoiceVat_'.$vat->id, 'VAT ('.$vat->name.'):', ['class'=>'col-sm-9 control-label']) !!}
		<div class="col-sm-2">
			{!! Form::text('invoiceVat_'.$vat->id, null , ['class'=>'form-control', 'placeholder'=>'', 'id'=>'invoiceVat_'.$vat->id, 'readonly'] ) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('invoiceTotal_'.$vat->id, 'Total with tax ('.$vat->name.'):', ['class'=>'col-sm-9 control-label']) !!}
		<div class="col-sm-2">
			{!! Form::text('invoiceTotal_'.$vat->id, null , ['class'=>'form-control', 'placeholder'=>'', 'id'=>'invoiceTotal_'.$vat->id, 'readonly'] ) !!}
		</div>
	</div>
	<hr>
</div>


@endforeach







<script type="text/javascript">
	$(document).ready(function(){
		calculateTotal();


		$(document.body).on('change', '.line-1',  function(){

			quantity = $(this).parent().parent().find('.line_quantity').val();
			price = $(this).parent().parent().find('.line_price').val();
			vat_id = $(this).parent().parent().find('.line_vat_id').val();
			// console.log('vat_id: '+vat_id);


			total =  (quantity*1 * price *1).toFixed(2);
			$(this).parent().parent().find('.line_total').val(total);

			calculateTotal();
		});

		$('#addLine').on('click', function(){
			div = $('#line-empty-div').clone().removeClass('hidden');
			div.find("input").val("");
			// div = $('#line-empty-div').clone();
			$(this).before(div);
		});

		$(document.body).on('click', '.remove-line',  function(){
			$(this).parent().remove();
			calculateTotal();

		});


		$('#dp1').datepicker({
			format: 'dd.mm.yyyy'
		});

		$('#dp2').datepicker({
			format: 'dd.mm.yyyy'
		});



	});
	function calculateTotal(){
		// totalWithoutTax = 0;
		// totalWithoutTax[1] = 0;
		// totalWithoutTax[2] = 0;
		// var totalWithoutTax_1 = 0;
		// var totalWithoutTax_2 = 0;

		var totalWithoutTax  = {
			'1' : 0.00,
			'2': 0.00,
			'3': 0.00,
			'4': 0.00,
			'5': 0.00
		};

		$(".line_total").each(function(){
			// console.log( $(this).next().val() );
			vat_id = $(this).parent().parent().find('.line_vat_id').val();
			console.log('vat_id: '+vat_id);

			// totalWithoutTax += $(this).val()*1;

			totalWithoutTax[ vat_id ] += $(this).val()*1;
			// if(totalWithoutTax[ vat_id ] > 0){
			// 	$(this).
			// }
			// if(vat_id == 1){
			// 	totalWithoutTax['1'] += $(this).val()*1;
			// } else if(vat_id == 2){
			// 	totalWithoutTax['2'] += $(this).val()*1;
			// }
		});
		



		

		var vats = {!! json_encode($vats) !!};
		console.log(vats);
		for (var key in vats) {
			// console.log('A:'+ totalWithoutTax[ vats[key].id ]);
			beforeTax = totalWithoutTax[ vats[key].id ] ;

			$('#invoiceBeforeTaxTotal_'+vats[key].id ).val( beforeTax );

			if(beforeTax > 0 ){
				
				$('#invoiceBeforeTaxTotal_'+vats[key].id ).parent().parent().parent().removeClass('hide');
				
			} else {

				$('#invoiceBeforeTaxTotal_'+vats[key].id ).parent().parent().parent().addClass('hide');
			}

			tax = totalWithoutTax[ vats[key].id ] * vats[key].rate;
			$('#invoiceVat_'+vats[key].id ).val( tax );
			total = tax*1 +  beforeTax*1;
			$('#invoiceTotal_'+vats[key].id ).val( total );
		}

	}


</script>



