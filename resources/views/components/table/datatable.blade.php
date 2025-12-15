<table id="{{ $tableId }}" class="table border-top dataTable no-footer collapsed table-hover text-nowrap">
  <thead class="table-light">
  <tr class="text-nowrap">
    <th>#</th>
    @foreach ($columns as $column)
      <th>{{ __($translationPrefix . '.' . $column) }}</th>
    @endforeach
  </tr>
  </thead>
</table>
