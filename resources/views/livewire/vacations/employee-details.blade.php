<div>
    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header">
                Employee details: <strong>{{$this->getEmployeeName()}}</strong>
                <div style="float: right">

                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <th>Date</th>
                        <th>used</th>
                        <th>earned</th>
                        <th>type</th>
                        <th>description</th>
                        <th>balance</th>
                    </thead>
                    <tbody>
                    @foreach(array_reverse($this->details()['items'] ?? []) as $index =>  $item)
                        <tr>
                            <td>{{$item->date ?? ''}}</td>
                            <td>{{$item->usedDays ?? ''}}</td>
                            <td>{{$item->earnedDays ?? ''}}</td>
                            <td><span class="badge" style="background-color: {{\App\Services\VacationService::$colorMap[$item->desc]['bgColor'] ?? ''}}; color: {{\App\Services\VacationService::$colorMap[$item->desc]['color'] ?? ''}}">{{$item->desc ?? ''}}</span></td>
                            <td>{{$item->description ?? ''}}</td>
                            <td>{{$item->accumulatedDays ?? ''}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
