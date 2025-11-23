<style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;800&display=swap');

    body {
        font-family: 'Cairo', sans-serif;
        background-color: #f9f9f9;
    }

    .modal-body {
        direction: rtl;
        padding: 20px;
    }

    .inventory-container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    h3 {
        font-weight: 800;
        font-size: 24px;
        margin-bottom: 30px;
    }

    .card2 {
        background-color: #fff;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        border-radius: 10px;
        width: 100%;
        max-width: 600px;
        margin-bottom: 25px;
        padding: 20px 25px;
    }

    .card-title {
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 10px;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .amount {
        font-weight: bold;
        color: #2980b9;
        margin-right: 10px;
    }

    li {
        color: #333;
        margin-bottom: 5px;
    }

    hr {
        margin: 25px 0;
    }
</style>

<div class="modal-body">
    <div class="inventory-container">
        <h3>المخزون الخاص {{ $investor->name }}</h3>

        <!-- الكمية المضافة -->
        <div class="card2">
            <div class="card-title">📦 الكمية المضافة: <span
                    class="amount">{{ $stocksWithTheSameCategoryInAddOperation->sum('quantity') }}</span></div>
            <ul>
                @foreach($stocksWithTheSameCategoryInAddOperation as $stock)
                    <li style="display: flex; justify-content: space-between;">
                        <span>{{ $stock->quantity }} من {{ $stock->category->name }} </span>
                        <span> بتاريخ  {{ $stock->created_at->format('Y-m-d') }}</span>
                        <span></span>
                    </li>

                @endforeach
            </ul>
            <div class="card-title">💰 السعر الإجمالي: <span
                    class="amount">{{ $stocksWithTheSameCategoryInAddOperation->sum('total_price_add') }}</span></div>
        </div>

        <!-- الكمية المنقصة -->
        <div class="card2">
            <div class="card-title">📤 الكمية المنقصة: <span
                    class="amount">{{ $stocksWithTheSameCategoryInSellOperation->sum('quantity') }}</span></div>
            <ul>
                @foreach($stocksWithTheSameCategoryInSellOperation as $stock)
                    <li style="display: flex; justify-content: space-between;">
                        <span>{{ $stock->quantity }} من {{ $stock->category->name }} </span>
                        <span> بتاريخ  {{ $stock->created_at->format('Y-m-d') }}</span>
                        <span></span>
                    </li>
                @endforeach
            </ul>
            <div class="card-title">💰 السعر الإجمالي: <span
                    class="amount">{{ $stocksWithTheSameCategoryInSellOperation->sum('total_price_sub') }}</span></div>
        </div>

        <!-- الكمية المطلوبة -->
        <div class="card2">
            <div class="card-title">📋 الكمية المطلوبة: <span class="amount">{{ $orders->sum('quantity') }}</span></div>
            <ul>
                @foreach($orders as $order)
                    <li style="display: flex; justify-content: space-between;">
                        <span>{{ $order->quantity }} من {{ $order->category->name }} </span>
                        <span> بتاريخ  {{ $order->created_at->format('Y-m-d') }}</span>
                        <span>الطلب رقم {{$order->order_number}}</span>
                    </li>
                @endforeach
            </ul>
            <div class="card-title">💰 السعر الإجمالي: <span class="amount">{{ $orders->sum('required_to_pay') }}</span>
            </div>
        </div>

        <!-- الكمية المتبقية -->
        <div class="card2">
            <div class="card-title">✅ الكمية المتبقية:
                <span class="amount">
                    {{ $stocksWithTheSameCategoryInAddOperation->sum('quantity') - ($stocksWithTheSameCategoryInSellOperation->sum('quantity') + $orders->sum('quantity')) }}
                </span>
            </div>
        </div>
    </div>
</div>

