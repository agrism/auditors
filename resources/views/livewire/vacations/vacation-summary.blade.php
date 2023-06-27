<div>
    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header">
                Vacation summary <strong></strong>
                <div style="float: right">
                    @if($activeEmployeeId)
                        <div class="btn btn-primary btn-xs m-0 py-0 " wire:click="setActiveEmployeeId('')"><span class="fa fa-arrow-circle-left"></span></div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($activeEmployeeId)
                    <livewire:vacations.employee-details :employeeId="$activeEmployeeId" />
                @else
                    <table class="table">
                        <thead>
                        <th>Order</th>
                        <th>id</th>
                        <th>name</th>
                        <th>balance</th>
                        <th>active</th>
                        </thead>
                        <tbody>
                        @foreach($this->employees() as $index =>  $employee)
                            <tr wire:click="setActiveEmployeeId({{$employee['employeeId'] ?? ''}})" style="cursor: pointer;">
                                <td>{{$index+1}}</td>
                                <td>{{$employee['employeeId'] ?? '-'}}</td>
                                <td>{{$employee['employeeName'] ?? '-'}}</td>
                                <td>{{$employee['vacationBalance'] ?? '-'}}</td>
                                <td>{{$employee['active'] ?? '-'}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
