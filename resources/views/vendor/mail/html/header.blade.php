<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (isset($logo))
                {{-- Este es el bloque que estamos personalizando --}}
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 7C4 5.89543 4.89543 5 6 5H18C19.1046 5 20 5.89543 20 7V19C20 20.1046 19.1046 21 18 21H6C4.89543 21 4 20.1046 4 19V7Z" stroke="#4F46E5" stroke-width="1.5"/>
                    <path d="M16 3V6M8 3V6" stroke="#4F46E5" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M4 10H20" stroke="#4F46E5" stroke-width="1.5"/>
                    <path d="M9.5 15.5L11.5 17.5L15.5 13.5" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>