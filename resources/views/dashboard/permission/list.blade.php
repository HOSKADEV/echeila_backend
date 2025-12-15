@extends('layouts/contentNavbarLayout')

@section('title', __('app.permissions'))

@section('page-style')
<style>
  .table-primary td {
    background-color: #e7f1ff !important;
    font-size: 0.95rem;
    padding: 0.75rem !important;
  }
  
  .table tbody tr:not(.table-primary):hover {
    background-color: #f8f9fa;
  }
  
  .ps-4 {
    padding-left: 2rem !important;
  }
  
  .permission-group-header i {
    margin-right: 0.5rem;
  }
</style>
@endsection

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('app.permissions') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.permissions') }}</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Bootstrap Table with Header - Light -->
  <div class="card">
    <h5 class="card-header">@lang('app.permissions')</h5>
    <div class="table-responsive text-nowrap">
      <table id="laravel_datatable" class="table">
        <thead class="table-light">
          <tr>
            <th>{{__('Permission')}}</th>
            {{-- <th>{{__('Name')}}</th> --}}
            @foreach ($roles as $role)
              <th>{{ $role->name }} <br> <small>({{ \App\Support\Enum\Roles::get_name($role->name) }})</small></th>
            @endforeach
          </tr>
        </thead>
        <tbody>
        @if (count($permissions))
          @foreach ($groupedPermissions as $groupKey => $groupPerms)
            <!-- Group Header -->
            <tr class="table-primary permission-group-header">
              <td colspan="{{ count($roles) + 1 }}" class="fw-bold">
                <i class="bx bx-folder"></i> {{ \App\Support\Enum\Permissions::get_group_name($groupKey) }}
              </td>
            </tr>
            
            <!-- Group Permissions -->
            @foreach ($groupPerms as $permission)
              <tr>
                <td class="ps-4">{{ \App\Support\Enum\Permissions::get_permission_slug($permission->name) }}</td>
                {{-- <td>{{ $permission->name }}</td> --}}

                @foreach ($roles as $role)
                  <td class="text-center">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                      <input type="checkbox"
                             class="form-check-input"
                             id="cb-{{ $role->id }}-{{ $permission->id }}"
                             name="roles[{{ $role->id }}][]"
                             value="{{ $permission->id }}"
                            {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                            {{ ($role->name === \App\Support\Enum\Roles::SUPER_ADMIN
                            && ($permission->name === \App\Support\Enum\Permissions::MANAGE_ROLES
                            || $permission->name === \App\Support\Enum\Permissions::MANAGE_PERMISSIONS)) ? 'disabled' : '' }}
                      >
                      <label class="custom-control-label d-inline" for="cb-{{ $role->id }}-{{ $permission->id }}">
                      </label>
                    </div>
                  </td>
                @endforeach
              </tr>
            @endforeach
          @endforeach
        @else
          <tr>
            <td colspan="4">{{__('No permissions found')}}</td>
          </tr>
        @endif
        </tbody>
      </table>
    </div>
  </div>
  <!-- Bootstrap Table with Header - Light -->
  <br><br>
  @if(true)
    <button type="button" id="add_role"
            class="btn btn-primary"
            style="float: {{app()->getLocale() == 'ar'? 'left':'right'}}; padding: 6px 12px; font-size: 14px; margin-top: 10px;"
            onclick="updatePermissions()">@lang('app.save')</button>
  @endif
@endsection

@section('page-script')
  <script>
    function updatePermissions() {
      // Initialize the structure for roles and their permissions
      const rolesPermissions = { roles: [] }; // Start with an object containing a roles array
      const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');

      checkboxes.forEach(checkbox => {
        const roleId = checkbox.name.match(/roles\[(\d+)\]/)[1]; // Extract role ID
        const permissionId = checkbox.value;

        // Check if the role already exists in the rolesPermissions array
        const roleIndex = rolesPermissions.roles.findIndex(role => role.id === roleId);

        if (roleIndex === -1) {
          // If the role doesn't exist, create a new role object
          rolesPermissions.roles.push({ id: roleId, permissions: [permissionId] });
        } else {
          // If the role exists, add the permission to the existing role
          rolesPermissions.roles[roleIndex].permissions.push(permissionId);
        }
      });

      // Send the data using Fetch API
      fetch('{{ route('permissions.update') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
        },
        body: JSON.stringify(rolesPermissions) // Convert the rolesPermissions object to JSON
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire({
              title: "@lang('app.success')",
              text: "@lang('app.updated_successful')",
              icon: 'success',
              confirmButtonColor: "{{primary_color()}}",
              confirmButtonText: "@lang('app.continue')"
            }).then((result) => {
              location.reload();
            });
          } else {
            console.log(data.message);
            Swal.fire({
              title: "@lang('app.error')",
              html: data.message,
              icon: 'error',
              confirmButtonColor: "{{primary_color()}}",
              confirmButtonText: "@lang('app.continue')"
            });
          }
        })
        .catch(error => {
          console.log(error.message);
          Swal.fire({
            title: "@lang('app.error')",
            html: error.message,
            icon: 'error',
            confirmButtonColor: "{{primary_color()}}",
            confirmButtonText: "@lang('app.continue')"
          });
        });
    }
  </script>
@endsection
