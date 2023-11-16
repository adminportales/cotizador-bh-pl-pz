<div>
    <a class="py-2 pl-3 pr-4 text-white rounded hover:border-secondary-500 md:border-0  md:p-0 hover:bg-transparent relative flex"
        aria-current="page" href="{{ route('cotizacion') }}">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
        </div>
        @if ($total > 0)
            <div>
                <span
                    class="bg-white text-black text-xs font-medium py-1 px-2 rounded-full absolute md:static top-0 right-0">{{ $total }}</span>
            </div>
        @endif
    </a>

    {{-- <a class="cart-icon nav-link text-white" aria-current="page" href="{{ route('cotizacion') }}" data-toggle="tooltip"
        data-placement="bottom" title="Cotizacion Actual">
        <div style="width: 2rem">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
        </div>
        @if ($total > 0)
            <span class="badge badge-light">{{ $total }}</span>
        @endif
    </a>
    <style>
        .cart-icon > s {
            position: absolute;
            top: 20px;
            right: 10px;
        }
        .cart-icon{
            position: relative;
        }
        @media(min-width:768px) {
            .cart-icon > s {
                position: initial;
            }
        }
    </style> --}}
</div>
