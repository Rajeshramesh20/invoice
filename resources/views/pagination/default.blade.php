@if ($paginator->hasPages())
    <style type="text/css">

        .pagination-wrapper {
            text-align: center;
            margin-top: 20px;
        }

        .pagination {
            display: inline-flex;
            list-style: none;
            padding: 0;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pagination li {
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            min-width: 30px;
            text-align: center;
        }

        .pagination li a {
            text-decoration: none;
            color: #333;
            display: block;
        }

        .pagination li.active {
            background-color: #333;
            color: white;
            font-weight: bold;
        }

        .pagination li.disabled {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
    
    <nav class="pagination-wrapper">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled">&laquo;</li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}">&laquo;</a></li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="disabled">{{ $element }}</li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active">{{ $page }}</li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}">&raquo;</a></li>
            @else
                <li class="disabled">&raquo;</li>
            @endif
        </ul>
    </nav>
@endif
