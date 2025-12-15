<!-- BEGIN: My Custom JS-->

{{--datatable initialization--}}
<script>
function initializeDataTable(route, columns, filters = {}, tableId = "#laravel_datatable") {
  const $table = $(tableId);
  const $search = $('#custom_search');
  const $length = $('#custom_length');

  const table = $table.DataTable({
    language: {{ \Illuminate\Support\Js::from(__('datatable')) }},
    responsive: false, // ← Disable responsive
    scrollX: true, // ← Enable horizontal scroll
    processing: true,
    serverSide: true,
    ajax: {
      url: route,
      type: "GET",
      data: d => {
        Object.assign(d, filters);
        d.length = $length.val();
        d.search.value = $search.val();
      }
    },
    pageLength: $length.val() ?? 10,
    columns: [
      {
        data: null,
        name: 'number',
        orderable: false,
        searchable: false,
        render: function (data, type, row, meta) {
          const pageInfo = $(meta.settings.nTable).DataTable().page.info();
          return pageInfo.start + meta.row + 1;
        }
      },
      ...columns.map(column =>
        column === 'action'
          ? { data: column, name: column, orderable: false, searchable: false }
          : { data: column, name: column }
      )
    ],
    dom: 'rt<"datatable-footer"ip>',
    drawCallback: function (settings) {
      const id = settings.nTable.id;
      const $wrapper = $(settings.nTableWrapper);

      $('#' + id + '_info_custom').html($wrapper.find('.dataTables_info').html());
      $('#' + id + '_paginate_custom').html($wrapper.find('.dataTables_paginate').html());

      $table.find('tbody').addClass('table-border-bottom-0');

      const api = this.api();
      const $customPaginate = $('#' + id + '_paginate_custom');

      $customPaginate.off('click', '.page-link').on('click', '.page-link', function (e) {
        e.preventDefault();
        const idx = $(this).data('dt-idx');

        if (idx === 'previous') {
          if (!$(this).parent().hasClass('disabled')) api.page('previous').draw('page');
        } else if (idx === 'next') {
          if (!$(this).parent().hasClass('disabled')) api.page('next').draw('page');
        } else {
          const page = parseInt(idx);
          if (!isNaN(page)) api.page(page).draw('page');
        }
      });
    }
  });

  $search.off('input').on('input', () => table.search($search.val()).draw());
  $length.off('change').on('change', () => table.page.len($length.val()).draw());

  return table;
}
</script>

{{--delete confirmation--}}
<script>
  function handleActionConfirmation(attr, confirmBtnText, formId, actionClass, actionUrlPlaceholder = ':id', value = null) {
    $(document).on('click', actionClass, function () {
      var id = $(this).attr(attr);
      Swal.fire({
        title: "@lang('app.are_you_sure')",
        text: "@lang('app.you_wont_be_able_to_revert_this')",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: confirmBtnText,
        cancelButtonText: "@lang('app.cancel')",
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          var form = $(formId);
          if (actionUrlPlaceholder) {
            form.attr('action', form.attr('action').replace(actionUrlPlaceholder, id));
            if (value) {
              form.attr('action', form.attr('action').replace(':' + value, value));
            }
          }
          form.submit();
        }
      });
    });
  }
</script>

{{--text editor--}}
<script>
  let quill; // Declare globally

  function createTextEditor(elementId) {
    const options = {
      debug: 'info', // Debug level
      modules: {
        toolbar: [
          [{ 'font': [] }],
          [{ 'size': ['small', false, 'large', 'huge'] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ 'color': [] }, { 'background': [] }],
          [{ 'script': 'sub' }, { 'script': 'super' }],
          [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
          [{ 'align': [] }],
          ['blockquote', 'code-block'],
          [{ 'list': 'ordered' }, { 'list': 'bullet' }],
          [{ 'indent': '-1' }, { 'indent': '+1' }],
          [{ 'direction': 'rtl' }],
          ['link', 'image', 'video'],
          ['clean']
        ]
      },
      placeholder: "@lang('app.write_something')",
      readOnly: false,
      theme: 'snow'
    };

    quill = new Quill(elementId, options); // Initialize Quill and assign it globally
  }

  function createBasicTextEditor(elementId) {
    const options = {
      debug: 'info', // Debug level
      modules: {
        toolbar: [
          [{ 'font': [] }],
          [{ 'size': ['small', false, 'large', 'huge'] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
          [{ 'align': [] }],
          [{ 'list': 'ordered' }, { 'list': 'bullet' }],
          [{ 'indent': '-1' }, { 'indent': '+1' }],
          [{ 'direction': 'rtl' }],
        ]
      },
      placeholder: "@lang('app.write_something')",
      readOnly: false,
      theme: 'snow'
    };

    quill = new Quill(elementId, options); // Initialize Quill and assign it globally
  }
</script>
<!-- END: My Custom JS-->
<script>
function initializeDateFilter(inputId) {
    const input = document.getElementById(inputId);
    const iconSpan = document.getElementById(inputId + '_icon');
    const icon = iconSpan.querySelector('i');

    // Update icon based on input value
    function updateIcon() {
        if (input.value) {
            icon.className = 'bx bx-calendar-x';
            iconSpan.title = 'Clear date';
        } else {
            icon.className = 'bx bx-calendar';
            iconSpan.title = 'Select date';
        }
    }

    // Clear date and trigger change event
    function clearDate() {
        if (input.value) {
            input.value = '';
            updateIcon();
            $(input).trigger('change'); // Trigger change for filter
        }
    }

    // Event listeners
    input.addEventListener('change', updateIcon);
    iconSpan.addEventListener('click', clearDate);

    // Initialize icon state
    updateIcon();
}
  </script>
