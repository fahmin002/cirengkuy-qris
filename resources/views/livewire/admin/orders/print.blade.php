<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.5; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .flex { display: flex; justify-content: space-between; }
        table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        table th { background: #f5f5f5; padding: 10px; border-bottom: 2px solid #ddd; }
        table td { padding: 10px; border-bottom: 1px solid #eee; }
        .total { font-size: 18px; font-weight: bold; text-align: right; margin-top: 20px; }
        .text-right { text-align: right; }
        /* Perintah Auto Print */
        @media print {
            .no-print { display: none; }
            .invoice-box { border: none; padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="background: #fff9c4; padding: 10px; text-align: center;">
        <button onclick="window.print()">Cetak Sekarang</button>
        <button onclick="window.close()">Tutup Halaman</button>
    </div>

    <div class="invoice-box">
        <div class="flex">
            <div>
                <h2 style="margin:0;">Cirengkuy</h2>
                <p>Order #{{ $order->order_number }}</p>
            </div>
            <div class="text-right">
                <strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
                <strong>Status:</strong> {{ strtoupper($order->status) }}
            </div>
        </div>

        <hr>

        <div class="flex">
            <div>
                <strong>Pelanggan:</strong><br>
                {{ $order->user->name }}<br>
                {{ $order->user->email }}
            </div>
            <div class="text-right">
                <strong>Alamat Pengiriman:</strong><br>
                {{ $order->recipient_name }} ({{ $order->recipient_phone }})<br>
                {{ $order->delivery_address }}
            </div>
        </div>

        <br>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Harga</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Total Bayar: Rp {{ number_format($order->total_price, 0, ',', '.') }}
        </div>
        
        <p style="margin-top: 50px; font-size: 12px; text-align: center; color: #888;">
            Terima kasih telah memesan di Cirengkuy!
        </p>
    </div>
</body>
</html>