<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
	<style media="all">
        @page {
			margin: 0;
			padding:0;
		}
		body{
			font-size: 0.875rem;
            font-family: '<?php echo  $font_family ?>';
            font-weight: normal;
            direction: <?php echo  $direction ?>;
            text-align: <?php echo  $text_align ?>;
			padding:0;
			margin:0;
		}
		.gry-color *,
		.gry-color{
			color:#878f9c;
		}
		table{
			width: 100%;
		}
		table th{
			font-weight: normal;
		}
		table.padding th{
			padding: .25rem .7rem;
		}
		table.padding td{
			padding: .25rem .7rem;
		}
		table.sm-padding td{
			padding: .1rem .7rem;
		}
		.border-bottom td,
		.border-bottom th{
			border-bottom:1px solid #eceff4;
		}
		.text-left{
			text-align:<?php echo  $text_align ?>;
		}
		.text-right{
			text-align:<?php echo  $not_text_align ?>;
		}
	</style>
</head>
<body>
	<div>

		@php
			$logo = get_setting('header_logo');
		@endphp

		<div style="background: #eceff4;padding: 1rem;">
			<table>
				<tr>
					<td>
{{--						@if($logo != null)--}}
							<img src="{{ uploaded_asset($logo) }}" height="30" style="display:inline-block;">
{{--						@else--}}
{{--							<img src="{{ static_asset('assets/img/logo.png') }}" height="30" style="display:inline-block;">--}}
{{--						@endif--}}
					</td>
					<td style="font-size: 1.5rem;" class="text-right strong">{{  translate('Permission Exchange') }}</td>
				</tr>
			</table>
			<table>
				<tr>
					<td style="font-size: 1rem;" class="strong">{{ get_setting('site_name') }}</td>
					<td class="text-right"></td>
				</tr>
				<tr>
					<td class="gry-color small">{{ get_setting('contact_address') }}</td>
					<td class="text-right"></td>
				</tr>
				<tr>
					<td class="gry-color small">{{  translate('Email') }}: {{ get_setting('contact_email') }}</td>
					<td class="text-right small"><span class="gry-color small">{{  translate('The exchange was done in Date') }}:</span> <span class="strong">{{ date('d-m-Y', strtotime($permission->exchange_at)) }}</span></td>
				</tr>
				<tr>
					<td class="gry-color small">{{  translate('Phone') }}: {{ get_setting('contact_phone') }}</td>
					<td class="text-right small"><span class="gry-color small">{{  translate('Permission Date') }}:</span> <span class=" strong">{{ date('d-m-Y',strtotime( $permission->date)) }}</span></td>
				</tr>
			</table>

		</div>

		<div style="padding: 1rem;padding-bottom: 0">
            <table>
				@php
				@endphp
				<tr><td class="strong small gry-color">{{ translate('Expense Item') }}: {{$permission->expense->name}}</td></tr>
				<tr><td class="strong">{{ translate('Expense By') }}: {{$permission->expense_by}}</td></tr>
				<tr><td class="gry-color small">{{ translate('Price')}}: {{single_price($permission->price)}}</td></tr>

			</table>
		</div>
<h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0" style="padding: 1rem;padding-bottom: 0" >{{translate('Description')}}</h3>
        {{$permission->description}}
	</div>
</body>
</html>
