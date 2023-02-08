<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    public function index() {
        Gate::authorize('view', 'orders');

        $orders = Order::paginate();

        return OrderResource::collection($orders);
    }

    public function show($order_id) {
        Gate::authorize('view', 'orders');

        return new OrderResource(Order::findOrFail($order_id));
    }

    public function export() {
        Gate::authorize('view', 'orders');

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=order.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];

        $callback = function () {
            $orders = Order::all();

            $file = fopen('php://output', 'w');

            // cabeçalho
            fputcsv($file, ['ID', 'Name', 'Email', 'Product Title', 'Price', 'Quantity']);

            foreach ($orders as $order) {
                foreach ($order->orderItems as $orderItem) {
                    fputcsv($file, [$order->id, $order->name, $order->email, $orderItem->product_title, $orderItem->price, $orderItem->quantity]);
                }
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
