@extends(backpack_view('blank'))

@push('after_styles')
  <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.15.4/css/all.css">

  @jxnCss

  <style>
    #jaxon-dbadmin .row, .modal-dialog .row {
      margin-bottom: 10px;
    }
    #jaxon-dbadmin table.table {
      margin-bottom: 16px;
    }
    #jaxon-dbadmin .btn {
      min-width: 0;
      min-height: 0;
    }
  </style>
@endpush

@push('after_scripts')
  @jxnJs

  @jxnScript

  <script type='text/javascript'>
    @jxnPackage(Lagdo\DbAdmin\Db\DbAdminPackage::class, 'ready')
  </script>
@endpush

@section('content')
<div class="card">
  <div class="card-body">
    <div style="margin:0 -10px; padding:5px; background-color:white; border-radius:5px;">
      <div style="margin-left:5px">
        <h2>Jaxon DB Admin</h2>
      </div>
      <div>
        {!! jaxon()->package(Lagdo\DbAdmin\Db\DbAdminPackage::class)->layout() !!}
      </div>
    </div>
  </div>
</div>
@endsection
