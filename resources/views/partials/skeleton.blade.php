{{--
Loading Skeleton Components
Usage:
    - @include('partials.skeleton', ['type' => 'card'])
    - @include('partials.skeleton', ['type' => 'table', 'rows' => 5])
    - @include('partials.skeleton', ['type' => 'text', 'lines' => 3])
--}}

@php
    $type = $type ?? 'text';
    $lines = $lines ?? 3;
    $rows = $rows ?? 5;
@endphp

<style>
    .skeleton {
        animation: skeleton-loading 1s linear infinite alternate;
        background: linear-gradient(90deg, #e8e8e8 25%, #f5f5f5 50%, #e8e8e8 75%);
        background-size: 200% 100%;
        border-radius: 4px;
    }

    .dark-style .skeleton {
        background: linear-gradient(90deg, #3a3a4f 25%, #4a4a5f 50%, #3a3a4f 75%);
        background-size: 200% 100%;
    }

    @keyframes skeleton-loading {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    .skeleton-text {
        height: 1rem;
        margin-bottom: 0.5rem;
    }

    .skeleton-title {
        height: 1.5rem;
        width: 60%;
        margin-bottom: 1rem;
    }

    .skeleton-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .skeleton-card-img {
        height: 150px;
        margin-bottom: 1rem;
    }

    .skeleton-button {
        height: 38px;
        width: 100px;
        border-radius: 6px;
    }
</style>

@if ($type === 'card')
    <div class="card mb-4">
        <div class="card-body">
            <div class="skeleton skeleton-card-img"></div>
            <div class="skeleton skeleton-title"></div>
            <div class="skeleton skeleton-text" style="width: 100%;"></div>
            <div class="skeleton skeleton-text" style="width: 80%;"></div>
            <div class="skeleton skeleton-text" style="width: 60%;"></div>
            <div class="d-flex justify-content-end mt-3">
                <div class="skeleton skeleton-button"></div>
            </div>
        </div>
    </div>
@elseif($type === 'table')
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    @for ($i = 0; $i < 4; $i++)
                        <th>
                            <div class="skeleton skeleton-text" style="width: 80%;"></div>
                        </th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @for ($r = 0; $r < $rows; $r++)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="skeleton skeleton-avatar me-2"></div>
                                <div class="skeleton skeleton-text" style="width: 100px;"></div>
                            </div>
                        </td>
                        @for ($c = 0; $c < 3; $c++)
                            <td>
                                <div class="skeleton skeleton-text" style="width: {{ rand(60, 100) }}%;"></div>
                            </td>
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
@elseif($type === 'list')
    <ul class="list-group list-group-flush">
        @for ($i = 0; $i < $lines; $i++)
            <li class="list-group-item d-flex align-items-center">
                <div class="skeleton skeleton-avatar me-3"></div>
                <div class="flex-grow-1">
                    <div class="skeleton skeleton-text" style="width: 70%;"></div>
                    <div class="skeleton skeleton-text" style="width: 50%; height: 0.75rem;"></div>
                </div>
            </li>
        @endfor
    </ul>
@elseif($type === 'stats')
    <div class="row">
        @for ($i = 0; $i < 4; $i++)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="skeleton skeleton-avatar"></div>
                            <div class="ms-3 flex-grow-1">
                                <div class="skeleton skeleton-text" style="width: 60%;"></div>
                            </div>
                        </div>
                        <div class="skeleton" style="height: 2rem; width: 50%;"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
@else
    {{-- text --}}
    @for ($i = 0; $i < $lines; $i++)
        <div class="skeleton skeleton-text" style="width: {{ $i === $lines - 1 ? '60%' : rand(80, 100) . '%' }};"></div>
    @endfor
@endif
