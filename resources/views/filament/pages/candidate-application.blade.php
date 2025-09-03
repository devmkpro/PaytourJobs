<x-filament-panels::page>
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                {{ __('Apply for a Position') }}
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300">
                {{ __('Fill out the form below to submit your job application. We\'ll review your information and get back to you soon.') }}
            </p>
        </div>
        
        {{ $this->content }}
    </div>
</x-filament-panels::page>
