@extends('layouts.app')
@section('title', __('sms_settings'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>sms_settings</h1>
</section>

<!-- Main content -->
<section class="content">
	{!! Form::open(['url' => action('/send_sms'), 'method' => 'post','files' => true ]) !!}
	<div class="box box-solid">
		<div class="box-body">
			<div class="row">
				
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('Sender Number'.':') !!}
						{!! Form::text('to', null, ['class' => 'form-control']); !!}
					</div>
				</div>
				<div class="clearfix"></div>
				
				
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('Message Body'. ':') !!}
								{!! Form::textarea('msg', null, ['class' => 'form-control', 'rows' => 3]); !!}
					</div>
				</div>
				
			</div>
		</div>
	</div> <!--box end-->
	<div class="col-sm-12 text-center">
		<button type="submit" class="btn btn-primary btn-big">@lang('messages.save')</button>
	</div>
{!! Form::close() !!}
</section>
@endsection
