@extends('admin.layout.admin')

@section('content')

    <div class="col-md-12">
        <h3>Vacations</h3>
        <form action="{{route('admin.vacations.handle')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="delete_history_event_id" value=""/>

            <div class="form-group col-ms-12">
                <select name="company_id" class="form-control">
                    @foreach($companies ?? [] as $company)
                        <option value="{{$company->id}}"
                                @if($company->active) selected @endif>{{$company->title}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-ms-12">
                <select name="employee_id" class="form-control">
                    @foreach($employees as $employee)
                        <option value="{{$employee->id}}"
                                @if($employee->active) selected @endif>{{$employee->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-ms-12">

                <input type="file" name="data" class="form-control" value="{{request()->data}}">

            </div>
            <div class="form-group col-ms-12">
                <button class="btn btn-default">Get data</button>
                <input class="btn btn-default" type="submit" name="recalculate_selected_employee" value="Recalculate selected employee">
                <input class="btn btn-default" type="submit" name="recalculate_for_all_company" value="Recalculate selected company all employees">
            </div>

        </form>

        <table class="table">
            <thead>
            <th>No</th>
            <th>Date</th>
            <th>descr.</th>
            <th>usedDays</th>
            <th>earnedDays</th>
            <th>Description</th>
            <th>Balance</th>
            <th>action</th>
            </thead>
            <tbody>
            @foreach(array_reverse($data['items'] ?? []) as $i => $item)
                <tr>
                    <td>{{$item->orderNo}}</td>
                    <td>{{$item->date}}</td>
{{--                    <td>{{$item->desc}}</td>--}}
                    <td><span class="badge" style="background-color: {{$colorMap[$item->desc]['bgColor'] ?? ''}}; color: {{$colorMap[$item->desc]['color'] ?? ''}}">{{$item->desc}}</span></td>
                    <td>{{$item->usedDays}}</td>
                    <td>{{$item->earnedDays}}</td>
                    <td>{{$item->description ?? null}}</td>
                    <td>{{$item->accumulatedDays}}</td>
                    <td>
                        @if($item->id)
                            <span class="btn btn-xs removeDateRecord" data-id="{{$item->id}}">x</span>
                        @endif
                            <button type="button" class="btn btn-primary btn-xs open-modal" data-toggle="modal__" data-target="#exampleModal____" data-date="{{$item->date}}">
                                Add
                            </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <thead>
            <th>-</th>
            <th>-</th>
            <th>-</th>
            <th>{{$data['totalUsed'] ?? '-'}}</th>
            <th>{{$data['totalEarned'] ?? '-'}}</th>
            <th>-</th>
            <th>-</th>
            <th>-</th>
            </thead>
        </table>


    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Register histry event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <label for="event-date">Event date</label>
                            <input type="text" name="event-date" value="" class="form-control">
                        </div>
                    <div class="form-group">
                        <label for="event-days">Event days</label>
                        <input type="text" name="event-days" value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="event-description">Description</label>
                        <input type="text" name="event-description" value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="event-type">Event type</label>
                        <select name="event-type" class="form-control">
                            @foreach($eventTypes ?? [] as $type)
                                <option value="{{$type}}">{{$type}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="register-event" type="button" class="btn btn-primary">Register event</button>
                </div>
            </div>
        </div>
    </div>
@stop


@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#exampleModal').find('input[name="event-date"]').datepicker({
                format: 'yyyy-mm-dd',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
                //                calendarWeeks: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6]

            });

            $('#dp2').datepicker({
                format: 'yyyy-mm-dd',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
                //                calendarWeeks: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6]

            });

            $('body').on('click', '.removeDateRecord', function () {
                let valueToDelete = $(this).data('id');
                $('input[name="delete_history_event_id"]').val(valueToDelete);
                if (valueToDelete && confirm("Are you sure to delete?")) {
                    $('form').submit();
                }
            });

            $('body').on('change', 'select', function () {
                $(this).closest('form').submit();
            });

            $('body').on('click', '.open-modal', function () {
                var initDate = $(this).attr('data-date');
                console.log(initDate)
                $('#exampleModal').modal('show');
                $('#exampleModal').find('input[name="event-date"]').val(initDate);
                $('#exampleModal').find('input[name="event-date"]').datepicker({
                    format: 'yyyy-mm-dd',
                    weekStart: 1,
                    todayBtn: "linked",
                    todayHighlight: true,
                    autoclose: true,
                    //                calendarWeeks: true,
                    daysOfWeekDisabled: [],
                    daysOfWeekHighlighted: [0, 6],
                    setValue: initDate

                })
                $('#exampleModal').find('input[name="event-date"]').datepicker('update', initDate);
            });

            $('body').on('click', '#register-event', function (){
                (['date', 'days', 'type', 'description']).forEach(function(item){
                    var find = '[name="event-'+item+'"]';
                    var createName = 'form_event_'+item+'';
                    $('form').find('[name="'+createName+'"]').remove();
                    $('<input type="text" name="'+createName+'" value="'+$('#exampleModal').find(find).val()+'" />').appendTo('form');
                });

                $('form').submit();

            });
        });

    </script>
@stop