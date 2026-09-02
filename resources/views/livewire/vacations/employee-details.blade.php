<div>
    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="card card-modern shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                    <i class="fa-solid fa-user-clock fs-5"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ __('Darbinieka atvaļinājumu vēsture') }}</h5>
                    <span class="small text-muted">{{ $this->getEmployeeName() }}</span>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Datums</th>
                            <th>Izmantots</th>
                            <th>Nopelnīts</th>
                            <th>Veids</th>
                            <th>Apraksts</th>
                            <th class="text-end">Bilance</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse(array_reverse($this->details()['items'] ?? []) as $index => $item)
                        <tr>
                            <td class="text-nowrap text-muted small">
                                <i class="fa-regular fa-calendar me-1"></i> {{ $item->date ?? '-' }}
                            </td>
                            <td>
                                @if(!empty($item->usedDays) && floatval($item->usedDays) > 0)
                                    <span class="badge bg-danger-50 text-danger border border-danger-subtle font-monospace">
                                        -{{ $item->usedDays }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($item->earnedDays) && floatval($item->earnedDays) > 0)
                                    <span class="badge bg-success-50 text-success border border-success-subtle font-monospace">
                                        +{{ $item->earnedDays }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($item->desc))
                                    <span class="badge" style="background-color: {{\App\Services\VacationService::$colorMap[$item->desc]['bgColor'] ?? '#e2e8f0'}}; color: {{\App\Services\VacationService::$colorMap[$item->desc]['color'] ?? '#334155'}}">
                                        {{ $item->desc }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $item->description ?? '-' }}
                            </td>
                            <td class="text-end fw-bold font-monospace text-slate-800">
                                {{ $item->accumulatedDays ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Nav reģistrētu atvaļinājumu ierakstu šim darbiniekam.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
