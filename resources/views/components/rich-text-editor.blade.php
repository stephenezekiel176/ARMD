@props(['name' => 'content', 'value' => '', 'required' => false])

<div x-data="richTextEditor()" x-init="initEditor()" class="space-y-2">
    <!-- Toolbar -->
    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-t-md p-2">
        <!-- Text Formatting -->
        <div class="flex items-center space-x-1">
            <button type="button" @click="format('bold')" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/>
                </svg>
            </button>
            <button type="button" @click="format('italic')" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4h6L8 20H2"/>
                </svg>
            </button>
            <button type="button" @click="format('underline')" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3M4 21h16"/>
                </svg>
            </button>
            
            <div class="border-l border-gray-300 dark:border-gray-600 h-6 mx-2"></div>
            
            <!-- Heading -->
            <select @change="formatHeading($event.target.value); $event.target.value=''" class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded">
                <option value="">Heading</option>
                <option value="h1">Heading 1</option>
                <option value="h2">Heading 2</option>
                <option value="h3">Heading 3</option>
                <option value="h4">Heading 4</option>
            </select>
            
            <!-- Lists -->
            <button type="button" @click="format('insertUnorderedList')" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded" title="Bullet List">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <button type="button" @click="format('insertOrderedList')" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded" title="Numbered List">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <!-- Import Options -->
        <div class="flex items-center space-x-2">
            <input type="file" @change="importFile($event)" accept=".txt,.html,.doc,.docx" class="hidden" x-ref="fileInput">
            <button type="button" @click="$refs.fileInput.click()" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Import File
            </button>
            <button type="button" @click="clearContent()" class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">
                Clear
            </button>
        </div>
    </div>

    <!-- Editor Area -->
    <div 
        contenteditable="true" 
        @input="updateContent($event.target.innerHTML)"
        x-html="content"
        class="min-h-[300px] p-4 border border-gray-300 dark:border-gray-600 rounded-b-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500"
        style="max-height: 500px; overflow-y: auto;"
    ></div>

    <!-- Hidden Input -->
    <input type="hidden" name="{{ $name }}" x-model="content" {{ $required ? 'required' : '' }}>
    
    <!-- Character Count -->
    <div class="text-sm text-gray-500 dark:text-gray-400 text-right">
        <span x-text="content.replace(/<[^>]*>/g, '').length"></span> characters
    </div>
</div>

<script>
function richTextEditor() {
    return {
        content: @json($value),
        
        initEditor() {
            // Enable design mode
            document.execCommand('defaultParagraphSeparator', false, 'p');
        },
        
        format(command, value = null) {
            document.execCommand(command, false, value);
        },
        
        formatHeading(tag) {
            if (tag) {
                document.execCommand('formatBlock', false, tag);
            }
        },
        
        updateContent(html) {
            this.content = html;
        },
        
        importFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = (e) => {
                const text = e.target.result;
                // For HTML files, insert directly
                if (file.name.endsWith('.html')) {
                    this.content = text;
                } else {
                    // For text files, convert line breaks to paragraphs
                    const paragraphs = text.split('\n\n').map(p => `<p>${p.replace(/\n/g, '<br>')}</p>`).join('');
                    this.content = paragraphs;
                }
            };
            
            if (file.name.endsWith('.html')) {
                reader.readAsText(file);
            } else {
                reader.readAsText(file);
            }
            
            // Reset file input
            event.target.value = '';
        },
        
        clearContent() {
            if (confirm('Are you sure you want to clear all content?')) {
                this.content = '';
            }
        }
    }
}
</script>

<style>
    [contenteditable]:focus {
        outline: none;
    }
    
    [contenteditable] h1 { @apply text-3xl font-bold my-4; }
    [contenteditable] h2 { @apply text-2xl font-bold my-3; }
    [contenteditable] h3 { @apply text-xl font-bold my-2; }
    [contenteditable] h4 { @apply text-lg font-semibold my-2; }
    [contenteditable] p { @apply my-2; }
    [contenteditable] ul { @apply list-disc list-inside my-2; }
    [contenteditable] ol { @apply list-decimal list-inside my-2; }
    [contenteditable] li { @apply my-1; }
    [contenteditable] a { @apply text-blue-600 underline; }
    [contenteditable] strong { @apply font-bold; }
    [contenteditable] em { @apply italic; }
    [contenteditable] u { @apply underline; }
</style>
