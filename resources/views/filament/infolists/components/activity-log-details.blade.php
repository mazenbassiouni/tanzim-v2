@php
    $activity = $getRecord();
@endphp

<div class="fi-in-entry-wrp">
    <div class="grid gap-y-2">
        <div class="flex items-center gap-x-3 justify-between ">
            <dt class="fi-in-entry-wrp-label inline-flex items-center gap-x-3">
                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                    التفاصيل
                </span>
            </dt>
        </div>

        <div class="grid auto-cols-fr gap-y-2">
            <dd class="">
                <div class="fi-in-key-value w-full rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10">
                    <table class="w-full table-auto divide-y divide-gray-200 dark:divide-white/5">
                        <thead>
                            <tr>
                                <th scope="col" class="px-3 py-2 text-start text-sm font-medium text-gray-700 dark:text-gray-200">
                                    الخانة
                                </th>

                                <th scope="col" class="px-3 py-2 text-start text-sm font-medium text-gray-700 dark:text-gray-200">
                                    من
                                </th>

                                <th scope="col" class="px-3 py-2 text-start text-sm font-medium text-gray-700 dark:text-gray-200">
                                    إلى
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 font-mono text-base dark:divide-white/5 sm:text-sm sm:leading-6">
                            @foreach ($activity['properties']['attributes'] ?? [] as $key => $value)
                                <tr class="divide-x divide-gray-200 dark:divide-white/5 rtl:divide-x-reverse">
                                    <td class="px-3 py-1.5" style="width: 10%;">
                                        {{ __('activities.attributes.'.strtolower(class_basename($activity->subject)).'.'.$key) }}
                                    </td>

                                    <td class="px-3 py-1.5" style="width: 45%;">
                                        @if ($activity->description === 'created')
                                            -
                                        @elseif (isset($activity['properties']['old'][$key]))  
                                            @php
                                                $oldValue = $activity['properties']['old'][$key];

                                                if (in_array($key, ['started_at', 'due_to', 'done_at'])) {
                                                    $oldValue = \Illuminate\Support\Carbon::parse($oldValue)->timezone(config('app.timezone'))->translatedFormat('l d/m/Y');
                                                } else {
                                                    switch ($key) {
                                                        case 'office_id':
                                                            $oldValue = \App\Models\Office::find($oldValue)?->name;
                                                            break;
                                                        case 'category_id':
                                                            $oldValue = \App\Models\Category::find($oldValue)?->name;
                                                            break;
                                                        case 'status':
                                                            $oldValue = \App\Enums\TaskStatusEnum::from($oldValue)->label();
                                                            break;
                                                    }
                                                }
                                            @endphp

                                            <span style="--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);" class="text-custom-600 dark:text-custom-400">
                                                {{ $oldValue }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-3 py-1.5" style="width: 45%;">
                                        @php
                                            if (in_array($key, ['started_at', 'due_to', 'done_at'])) {
                                                $value = \Illuminate\Support\Carbon::parse($value)->timezone(config('app.timezone'))->translatedFormat('l d/m/Y');
                                            } else {
                                                switch ($key) {
                                                    case 'office_id':
                                                        $value = \App\Models\Office::find($value)?->name;
                                                        break;
                                                    case 'category_id':
                                                        $value = \App\Models\Category::find($value)?->name;
                                                        break;
                                                    case 'status':
                                                        $value = \App\Enums\TaskStatusEnum::from($value)->label();
                                                        break;
                                                }
                                            }
                                        @endphp

                                        <span style="--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);" class="text-custom-600 dark:text-custom-400">
                                            {{ $value }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </dd>
        </div>
    </div>
</div>