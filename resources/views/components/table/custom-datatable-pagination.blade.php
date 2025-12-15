  <div class="row mx-2 datatable-footer-manual">
  <div class="col-sm-12 col-md-6">
    <div id="{{ $tableId }}_info_custom" class="dataTables_info pt-0 pb-0" role="status" aria-live="polite"></div>
  </div>
  <div class="col-sm-12 col-md-6">
    <div id="{{ $tableId }}_paginate_custom" class="dataTables_paginate paging_simple_numbers  pt-0 pb-0"></div>
  </div>
</div>
<style>
  .datatable-footer {
    display: none !important;
  }
  .dataTables_paginate {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
  }
</style>
