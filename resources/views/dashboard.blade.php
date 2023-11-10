<x-app-layout>
    <livewire:tokens />

    <div class="col-span-full xl:col-span-12 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700 mt-4">
        <header class="p-4 border-b border-slate-100 dark:border-slate-700">
            <h2 class="font-semibold text-slate-800 dark:text-slate-100">Wise balance: <p class="font-normal">{{ $balance }} EUR</p></h2>
        </header>
        <table class="table-autodark:text-slate-300 mx-auto w-full sortable">
            <thead class="text-xs uppercase text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-700 dark:bg-opacity-50 rounded-sm cursor-pointer">
                <tr>
                    <th class="p-2 text-left pl-5">
                        Title
                    </th>
                    <th class="p-2 text-left pl-5">
                        Type
                    </th>
                    <th class="p-2 text-left pl-5">
                        Status
                    </th>
                    <th class="p-2 text-left pl-5">
                        Primary Amount
                    </th>
                    <th class="p-2 text-left pl-5">
                        Secondary Amount
                    </th>
                    <th class="p-2 text-left pl-5">
                        Created On
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($transactions as $transaction)
                    <tr>
                        <td class="p-2 w-1/4 text-slate-800 dark:text-slate-100 pl-3">
                            {!! $transaction->title !!}
                        </td>
                        <td class="p-2 text-slate-800 dark:text-slate-100">
                            {{ $transaction->type }}
                        </td>
                        <td class="p-2 text-slate-800 dark:text-slate-100">
                            {{ $transaction->status }}
                        </td>
                        <td class="p-2 text-slate-800 dark:text-slate-100">
                            {!! $transaction->primaryAmount !!}
                        </td>
                        <td class="p-2 text-slate-800 dark:text-slate-100">
                            {{ $transaction->secondaryAmount }}
                        </td>
                        <td class="p-2 text-slate-800 dark:text-slate-100">
                            {{ $transaction->createdOn }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
