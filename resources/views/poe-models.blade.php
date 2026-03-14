<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poe AI Models</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-900 font-sans antialiased">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <header class="mb-10 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Poe Model Explorer
                </h1>
                <p class="mt-2 text-lg text-gray-600">Browse and compare available AI models via Poe API.</p>
            </div>
            <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-semibold">
                Total Models: {{ count($sortedModels ?? []) }}
            </div>
        </header>

        @if (isset($error))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-8">
                <p class="text-red-700">{{ $error }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($sortedModels as $model)
                @php
                    // Format date as dd-mm-yyyy
                    $createdAt = \Carbon\Carbon::createFromTimestampMs($model['created'])->format('d-m-Y');
                    $displayName = $model['metadata']['display_name'] ?? $model['id'];
                    $image = $model['metadata']['image']['url'] ?? null;
                @endphp

                <div
                    class="flex flex-col bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300 overflow-hidden">
                    <!-- Card Header -->
                    <div class="p-6 flex-1">
                        <div class="flex items-center space-x-4 mb-4">
                            @if ($image)
                                <img src="{{ $image }}" alt="{{ $displayName }}"
                                    class="w-12 h-12 rounded-lg object-cover bg-gray-100">
                            @else
                                <div
                                    class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center text-gray-500 font-bold uppercase">
                                    {{ substr($model['id'], 0, 2) }}
                                </div>
                            @endif
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 leading-tight">{{ $displayName }}</h2>
                                <span
                                    class="inline-block px-2 py-0.5 mt-1 text-xs font-medium bg-gray-100 text-gray-600 rounded">
                                    {{ $model['owned_by'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-sm text-gray-600 line-clamp-3 mb-4">
                            {{ $model['description'] ?? 'No description available.' }}
                        </p>

                        <!-- Meta Info -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Created:</span>
                                <span class="font-mono text-gray-700">{{ $createdAt }}</span>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Context Window:</span>
                                <span class="text-gray-700">{{ number_format($model['context_length'] ?? 0) ?: 'N/A' }}
                                    tokens</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <div class="flex flex-wrap gap-2">
                            @foreach ($model['architecture']['input_modalities'] ?? [] as $modality)
                                <span
                                    class="px-2 py-1 text-[10px] uppercase font-bold tracking-wider rounded bg-blue-50 text-blue-600 border border-blue-100">
                                    {{ $modality }}
                                </span>
                            @endforeach

                            @if ($model['pricing'])
                                <span class="ml-auto text-xs font-semibold text-green-600">
                                    Paid
                                </span>
                            @else
                                <span class="ml-auto text-xs font-semibold text-gray-400">
                                    Free / Request-based
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>

</html>
