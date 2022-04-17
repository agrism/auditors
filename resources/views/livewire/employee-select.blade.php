<div>
    <div class="input-group input-group-sm" style="width: 100%">
        <select wire:model="selectedEmployeeId" class="form-control form-control-sm" name="employee_id">
            @foreach($employees ?? [] as $employee)
                <option value="{{$employee['id']}}">{{$employee['name']}}</option>
            @endforeach
        </select>
        <span id="basic-addon1"
              data-bs-toggle="modal"
              role="button"
              wire:click="edit({{ $selectedEmployeeId }})">
            <div class="input-group-append">
                <span class="input-group-text fa fa-edit"></span>
            </div>
{{--            <div type="button1" class="btn btn-xs fa fa-edit" style="cursor: pointer;"></div>--}}
        </span>
    </div>


    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="employeeEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #b9d4e2;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header info">
                    <h4 class="modal-title">{{ $selectedEmployeeId > 0 ? 'Edit' : 'Create' }} Employee</h4>
                    <button type="button" class="btn-close" aria-label="Close" wire:click="cancel()"></button>
                </div>
                <div class="modal-body" style1="margin-left: 15px;margin-right: 15px">
                    <div class="mb-1">
                        <label for="" style="font-size: 14px;">Name <br><span style="color: green">VALID: Bērziņš Dainis</span><br><span style="color:red;text-decoration: line-through;">NOT VALID: Dainis Bērziņš</span></label>
                        <input type="text" class="form-control @error('selectedEmployeeName')is-invalid @enderror"
                               placeholder="Employee name"
                               aria-describedby="basic-addon1" wire:model.defer="selectedEmployeeName">
                        @error('selectedEmployeeName') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-1">
                        <label for="" style="font-size: 12px;">Reg.No</label>
                        <input type="text" class="form-control @error('selectedEmployeeRegNo')is-invalid @enderror"
                               placeholder="Reg. No" aria-describedby="basic-addon1"
                               wire:model.defer="selectedEmployeeRegNo">
                        @error('selectedEmployeeRegNo') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-1">
                        <label for="" style="font-size: 12px;">Reg.No</label>
                        <input type="text" class="form-control @error('selectedEmployeeRole')is-invalid @enderror"
                               placeholder="Role" aria-describedby="basic-addon1"
                               wire:model.defer="selectedEmployeeRole">
                        @error('selectedEmployeeRole') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancel()">Close</button>
                    <button type="button" class="btn btn-primary" wire:click.prevent="save()">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <script type="text/javascript">
        window.addEventListener('employee_modal_open', event => {
            $('#employeeEditModal').modal('show');
        })

        window.addEventListener('employee_modal_close', () => {
            $('#employeeEditModal').modal('hide');
        });
    </script>

</div>