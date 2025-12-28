{{--
Breadcrumbs Partial
Usage: @include('partials.breadcrumbs', ['breadcrumbs' => [
    ['name' => 'Home', 'url' => route('home')],
    ['name' => 'Users', 'url' => route('users.index')],
    ['name' => 'Create'] // No URL = active/current page
]])
--}}

@if (isset($breadcrumbs) && count($breadcrumbs) > 0)
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb breadcrumb-style1">
            @foreach ($breadcrumbs as $index => $crumb)
                @if (isset($crumb['url']) && $index < count($breadcrumbs) - 1)
                    <li class="breadcrumb-item">
                        <a href="{{ $crumb['url'] }}">
                            @if ($index === 0)
                                <i class="bx bx-home-circle"></i>
                            @endif
                            {{ $crumb['name'] }}
                        </a>
                    </li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">{{ $crumb['name'] }}</li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
