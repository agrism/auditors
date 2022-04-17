<div>
    <div class="card card-default">
        <div class="card-header">

            <div class="table-responsive">
                <h5>Select company:</h5>
                <ul>
                    @foreach($companies as $company)
                        <li wire:click="setActiveCompanyId({{$company->id}})" role="button">
                            <div
                                    @if($company->id == \App\Services\AuthUser::instance()->selectedCompanyId()) style="background-color: limegreen; color: white" @endif
                            >
                                {{$company->title}}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
