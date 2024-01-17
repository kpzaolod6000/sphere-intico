<div class="col-xxl-12">
    <div class="card">
        <div class="card-header">

            <h5>{{ __('Goal') }}</h5>
        </div>
        <div class="card-body">
            @forelse($goals as $goal)
                @php
                    $total = $goal->target($goal->type, $goal->from, $goal->to, $goal->amount)['total'];
                    $percentage = $goal->target($goal->type, $goal->from, $goal->to, $goal->amount)['percentage'];
                @endphp
                <div class="card border-primary border-2 border-bottom-0 border-start-0 border-end-0">
                    <div class="card-body">
                        <div class="form-check p-0">
                            <label class="form-check-label d-block" for="customCheckdef1">
                                <span>
                                    <span class="row align-items-center mt-3">
                                        <span class="col">
                                            <span class="text-muted text-sm">{{ __('Name') }}</span>
                                            <h6 class="text-nowrap mb-3 mb-sm-0">{{ $goal->name }}
                                            </h6>
                                        </span>
                                        <span class="col">
                                            <span class="text-muted text-sm">{{ __('Type') }}</span>
                                            <h6 class="mb-3 mb-sm-0">
                                                {{ __(Modules\Goal\Entities\Goal::$goalType[$goal->type]) }}
                                            </h6>
                                        </span>
                                        <span class="col">
                                            <span class="text-muted text-sm">{{ __('Duration') }}</span>
                                            <h6 class="mb-3 mb-sm-0">
                                                {{ $goal->from . ' To ' . $goal->to }}</h6>
                                        </span>
                                        <span class="col">
                                            <span class="text-muted text-sm">{{ __('Target') }}</span>
                                            <h6 class="mb-3 mb-sm-0">
                                                {{ currency_format_with_sym($total) . ' of ' . currency_format_with_sym($goal->amount) }}
                                            </h6>
                                        </span>
                                        <span class="col">
                                            <span class="text-muted text-sm">{{ __('Progress') }}</span>
                                            <h6 class="mb-3 mb-sm-0">
                                                {{ number_format($goal->target($goal->type, $goal->from, $goal->to, $goal->amount)['percentage'], company_setting('currency_format_with_sym'), '.', '') }}%
                                            </h6>
                                            <div class="progress mb-0">
                                                <div class="progress-bar bg-primary"
                                                    style="width: {{ number_format($goal->target($goal->type, $goal->from, $goal->to, $goal->amount)['percentage'], company_setting('currency_format_with_sym'), '.', '') }}%">
                                                </div>
                                            </div>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            @empty
                @include('layouts.nodatafound')
            @endforelse
        </div>
    </div>
</div>
