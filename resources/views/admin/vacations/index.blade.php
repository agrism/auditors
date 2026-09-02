@extends('admin.layout.admin')

@section('content')
    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-umbrella-beach fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Atvaļinājumu un prombūtņu uzskaite') }}</h5>
                        <span class="small text-muted">{{ __('Pārskatiet un administrējiet darbinieku atvaļinājumu bilanci un notikumus') }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 border-bottom bg-slate-50">
                <form action="{{ route('admin.vacations.handle') }}" method="post" enctype="multipart/form-data" id="vacationForm">
                    @csrf
                    <input type="hidden" name="delete_history_event_id" value=""/>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="company_id" class="form-label small fw-semibold text-muted mb-1">{{ __('Uzņēmums') }}</label>
                            <select name="company_id" id="company_id" class="form-select form-select-sm">
                                @foreach($companies ?? [] as $company)
                                    <option value="{{ $company->id }}" @if($company->active) selected @endif>{{ $company->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="employee_id" class="form-label small fw-semibold text-muted mb-1">{{ __('Darbinieks') }}</label>
                            <select name="employee_id" id="employee_id" class="form-select form-select-sm">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" @if($employee->active) selected @endif>{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="data" class="form-label small fw-semibold text-muted mb-1">{{ __('Importēt datus (fails)') }}</label>
                            <input type="file" name="data" id="data" class="form-control form-control-sm" value="{{ request()->data }}">
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-modern btn-modern-primary btn-sm">
                            <i class="fa-solid fa-arrows-rotate me-1"></i> {{ __('Atlasīt datus') }}
                        </button>

                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-modern btn-modern-secondary btn-sm" type="submit" name="recalculate_selected_employee" value="Recalculate selected employee">
                                <i class="fa-solid fa-calculator me-1"></i> {{ __('Pārrēķināt darbiniekam') }}
                            </button>
                            <button class="btn btn-modern btn-modern-secondary btn-sm" type="submit" name="recalculate_for_all_company" value="Recalculate selected company all employees">
                                <i class="fa-solid fa-users me-1"></i> {{ __('Pārrēķināt visam uzņēmumam') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                        <tr>
                            <th style="width: 60px;">Nr.</th>
                            <th style="width: 110px;">{{ __('Datums') }}</th>
                            <th style="width: 130px;">{{ __('Veids') }}</th>
                            <th class="text-center" style="width: 130px;">{{ __('Izmantotās d.') }}</th>
                            <th class="text-center" style="width: 130px;">{{ __('Nopelnītās d.') }}</th>
                            <th>{{ __('Apraksts') }}</th>
                            <th class="text-center" style="width: 110px;">{{ __('Bilance') }}</th>
                            <th class="text-end" style="width: 100px;">{{ __('Darbības') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse(array_reverse($data['items'] ?? []) as $i => $item)
                            <tr class="line text-truncate">
                                <td class="text-muted small">{{ $item->orderNo }}</td>
                                <td class="text-muted small">{{ $item->date }}</td>
                                <td>
                                    <span class="badge rounded-pill px-2.5 py-1" style="background-color: {{ $colorMap[$item->desc]['bgColor'] ?? '#f1f5f9' }}; color: {{ $colorMap[$item->desc]['color'] ?? '#334155' }}">
                                        {{ $item->desc }}
                                    </span>
                                </td>
                                <td class="text-center font-monospace text-slate-700">
                                    {{ $item->usedDays ?: '-' }}
                                </td>
                                <td class="text-center font-monospace text-slate-700">
                                    {{ $item->earnedDays ?: '-' }}
                                </td>
                                <td class="text-truncate small text-muted">
                                    {{ $item->description ?? '-' }}
                                </td>
                                <td class="text-center font-monospace fw-bold {{ floatval($item->accumulatedDays) >= 0 ? 'text-emerald-600' : 'text-danger' }}">
                                    {{ $item->accumulatedDays }}
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs open-modal"
                                                style="width: 26px; height: 26px;"
                                                data-date="{{ $item->date }}"
                                                title="{{ __('Pievienot notikumu') }}">
                                            <i class="fa-solid fa-plus" style="font-size: 0.75rem;"></i>
                                        </button>
                                        @if($item->id)
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs removeDateRecord"
                                                    style="width: 26px; height: 26px;"
                                                    data-id="{{ $item->id }}"
                                                    title="{{ __('Dzēst ierakstu') }}">
                                                <i class="fa-solid fa-trash-can" style="font-size: 0.75rem;"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-slate-400"></i>
                                    {{ __('Nav atrasts neviens atvaļinājuma ieraksts.') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                        @if(isset($data['items']) && count($data['items']) > 0)
                            <tfoot>
                            <tr class="bg-slate-50 border-top fw-semibold">
                                <td colspan="3" class="text-end text-slate-700 py-3">{{ __('KOPĀ:') }}</td>
                                <td class="text-center font-monospace text-slate-800 py-3">{{ $data['totalUsed'] ?? '0' }}</td>
                                <td class="text-center font-monospace text-slate-800 py-3">{{ $data['totalEarned'] ?? '0' }}</td>
                                <td colspan="3"></td>
                            </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Register Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="exampleModalLabel">
                        <i class="fa-solid fa-calendar-plus me-2"></i> {{ __('Reģistrēt prombūtnes notikumu') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="event-date" class="form-label small fw-semibold text-muted mb-1">{{ __('Notikuma datums') }} *</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
                            <input type="text" name="event-date" id="event-date" class="form-control form-control-sm" placeholder="YYYY-MM-DD">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="event-days" class="form-label small fw-semibold text-muted mb-1">{{ __('Dienu skaits') }} *</label>
                        <input type="number" step="0.5" name="event-days" id="event-days" class="form-control form-control-sm" placeholder="Piem., 1, 2, 0.5...">
                    </div>

                    <div class="mb-3">
                        <label for="event-type" class="form-label small fw-semibold text-muted mb-1">{{ __('Notikuma veids') }} *</label>
                        <select name="event-type" id="event-type" class="form-select form-select-sm">
                            @foreach($eventTypes ?? [] as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label for="event-description" class="form-label small fw-semibold text-muted mb-1">{{ __('Apraksts / Komentārs') }}</label>
                        <input type="text" name="event-description" id="event-description" class="form-control form-control-sm" placeholder="Piem., Ikgadējais atvaļinājums...">
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-2 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-modern btn-modern-secondary btn-sm" data-bs-dismiss="modal">{{ __('Aizvērt') }}</button>
                    <button id="register-event" type="button" class="btn btn-modern btn-modern-primary btn-sm">
                        <i class="fa-solid fa-check me-1"></i> {{ __('Reģistrēt notikumu') }}
                    </button>
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
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6]
            });

            $('body').on('click', '.removeDateRecord', function () {
                let valueToDelete = $(this).data('id');
                $('input[name="delete_history_event_id"]').val(valueToDelete);
                if (valueToDelete && confirm("Vai tiešām vēlaties dzēst šo notikumu?")) {
                    $('#vacationForm').submit();
                }
            });

            $('body').on('change', '#company_id, #employee_id', function () {
                $('#vacationForm').submit();
            });

            $('body').on('click', '.open-modal', function () {
                var initDate = $(this).attr('data-date');
                const modalEl = document.getElementById('exampleModal');
                if (modalEl) {
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                }
                $('#exampleModal').find('input[name="event-date"]').val(initDate);
                $('#exampleModal').find('input[name="event-date"]').datepicker('update', initDate);
            });

            $('body').on('click', '#register-event', function (){
                (['date', 'days', 'type', 'description']).forEach(function(item){
                    var find = '[name="event-'+item+'"]';
                    var createName = 'form_event_'+item+'';
                    $('#vacationForm').find('[name="'+createName+'"]').remove();
                    $('<input type="hidden" name="'+createName+'" value="'+$('#exampleModal').find(find).val()+'" />').appendTo('#vacationForm');
                });

                $('#vacationForm').submit();
            });
        });
    </script>
@endsection