<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <!-- Секция заказов -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Мои заказы') }}
                        </h2>
                    </header>

                    <div class="mt-6">
                        @if(count($orders) > 0)
                            @foreach($orders as $order)
                                <div class="mb-6 p-4 border rounded-lg">
                                    <div class="flex justify-between items-center mb-4">
                                        <div>
                                            <span class="font-bold">Заказ #{{ $order['number'] }}</span>
                                            <span class="ml-4 text-gray-600">{{ $order['date'] }}</span>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-sm 
                                            @if($order['status'] === 'Новый') bg-blue-100 text-blue-800
                                            @elseif($order['status'] === 'Подтвержден') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $order['status'] }}
                                        </span>
                                    </div>

                                    <table class="min-w-full">
                                        <thead>
                                            <tr>
                                                <th class="text-left">Товар</th>
                                                <th class="text-right">Цена</th>
                                                <th class="text-right">Количество</th>
                                                <th class="text-right">Сумма</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order['products'] as $product)
                                                <tr>
                                                    <td class="py-2">{{ $product['title'] }}</td>
                                                    <td class="text-right">{{ number_format($product['price'], 2) }} ₽</td>
                                                    <td class="text-right">{{ $product['qty'] }}</td>
                                                    <td class="text-right">{{ number_format($product['price'] * $product['qty'], 2) }} ₽</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" class="text-right font-bold">Итого:</td>
                                                <td class="text-right">{{ $order['totalQty'] }}</td>
                                                <td class="text-right">{{ number_format($order['totalPrice'], 2) }} ₽</td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    @if($order['status'] === 'Новый')
                                        <div class="mt-4 text-right">
                                            <form action="{{ route('order.delete', $order['number']) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                                    Отменить заказ
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-600">У вас пока нет заказов.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
