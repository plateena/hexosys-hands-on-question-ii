<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Content Moderator') }}
        </h2>
    </x-slot>

    <div class="py-12" id="upload-section">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div>
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <div class="px-4 sm:px-0">
                                    <h3 class="text-lg font-medium text-gray-900 leading-6">FALCON API (V1.0)</h3>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Falcon offers an API service to integrate an accurate image content moderator into your applications and websites With the Falcon function, you can identify certain scenes in images that are specific to your business needs. for more detail please click <a class="text-indigo-600 hover:text-indigo-900" href="https://www.hexosys.com/falcon">here</a>
                                    </p>
                                </div>
                                <div class="px-4 sm:px-0">
                                    <vue-json-pretty class="mt-1 text-sm" :data="result"></vue-json-pretty>
                                </div>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <form name="url-submission" @submit.prevent="processUrlForm">
                                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                                            <div class="grid grid-cols-3 gap-6">
                                                <div class="col-span-3 sm:col-span-2">
                                                    <label for="image_url" class="block text-sm font-medium text-gray-700">
                                                        Image URL
                                                    </label>
                                                    <div class="flex mt-1 rounded-md shadow-sm">
                    <span class="inline-flex items-center px-3 text-sm text-gray-500 border border-r-0 border-gray-300 rounded-l-md bg-gray-50">
                      http://
                    </span>
                                                        <input type="text" name="image_url" id="image_url" class="flex-1 block w-full border-gray-300 rounded-none focus:ring-indigo-500 focus:border-indigo-500 rounded-r-md sm:text-sm" placeholder="www.example.com" v-model="imageUrl">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-4 py-3 text-right bg-gray-50 sm:px-6">
                                            <button type="submit"
                                                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent shadow-sm rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div class="gap-y-2">&nbsp</div>
                                <form action="#" method="POST">
                                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                                            <div>
                                                <div
                                                    @drop="fileDrop"
                                                    @dragover.prevent
                                                    @dragenter.prevent
                                                    class="flex justify-center px-6 pt-5 pb-6 mt-1 border-2 border-gray-300 border-dashed rounded-md">
                                                    <div class="text-center space-y-1">
                                                        <svg class="w-12 h-12 mx-auto text-gray-400"
                                                             stroke="currentColor" fill="none" viewBox="0 0 48 48"
                                                             aria-hidden="true">
                                                            <path
                                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                                stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round"/>
                                                        </svg>
                                                        <div class="flex text-sm text-gray-600">
                                                            <label for="file-upload"
                                                                   class="relative font-medium text-indigo-600 bg-white cursor-pointer rounded-md hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                                <span>Upload a file</span>
                                                                <input id="file-upload" name="file-upload" type="file"
                                                                       class="sr-only" @change="processFileChange($event.target.name, $event.target.files[0]);">
                                                            </label>
                                                            <p class="pl-1">or drag and drop</p>
                                                        </div>
                                                        <p class="text-xs text-gray-500">
                                                            JPG up to 10MB
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/moderator.js') }}" type="text/javascript"></script>
    @endpush
</x-app-layout>
