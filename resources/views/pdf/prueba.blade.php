<!DOCTYPE html>
<html lang="en">

<head>
    
</head>

<body>
    <div class="body-pdf">
        <div class="content body-products">
            @foreach ($quote->latestQuotesUpdate->quoteProducts as $item)
                @php
                    $producto = json_decode($item->product);
                    $tecnica = json_decode($item->technique);
                    $scales_info = json_decode($item->scales_info);
                    if ($item->quote_by_scales) {
                        $quote_scales = true;
                    }
                @endphp
                <table style="border-collapse: collapse;width:100%;border: 1px solid #72C3D6;">
                    <tr class="title">
                        <td>
                            <p class="title-text">Imagen de Referencia</p>
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="{{ $item->quote_by_scales ? 5 + count($scales_info) : 6 }}"
                            style="width: 230px; text-align: center; vertical-align: middle; padding: 0; border-right: 1px solid #72C3D6;">
                            @if ($producto->image)
                                @php
                                    $imageSrc = trim($producto->image);
                                @endphp
                                <img id="productImage" style="max-height: 100px; max-width: 100px;" src="{{ $imageSrc }}" alt="">
                                {{ $imageSrc }}
                            @else
                                <img src="img/default.jpg" width="180">
                            @endif
                        </td>
                    </tr>
    
                </table>
            @endforeach
        </div>
        <br>
    </div>
</body>

</html>
