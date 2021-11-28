{{Form::open(array('url'=>'user','method'=>'post'))}}
<div class="form-group">
    {{Form::label('name',__('Name'),array('class'=>'')) }}
    {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter User Name'),'required'=>'required'))}}
</div>
<div class="form-group">
    {{Form::label('email',__('Email'))}}
    {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email'),'required'=>'required'))}}
</div>
<div class="form-group">
    {{Form::label('password',__('Password'))}}
    {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter User Password'),'required'=>'required','minlength'=>"6"))}}
</div>
<div class="modal-footer">
    {{Form::submit(__('Create'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
</div>
{{Form::close()}}



