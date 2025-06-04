<div x-data="avatarComponent({{ json_encode($avatarUrl) }})" class="relative inline-block group">
    <div
        {{ $attributes->merge(['class' => "flex items-center justify-center $size $shape overflow-hidden bg-white border-2 border-gray-200 dark:bg-gray-700 dark:border-gray-600"]) }}>

        <!-- Loading Spinner -->
        <div x-show="isLoading"
            class="absolute inset-0 flex items-center justify-center bg-white/70 dark:bg-gray-700/70 z-10">
            <x-icon-loader-2 class="absolute size-20 text-gray-500 animate-spin" />
        </div>

        <!-- Avatar Image -->
        <img x-show="isShowingImage() && !isLoading" :src="previewUrl" alt="{{ $user?->full_name ?? 'Avatar' }}"
            class="object-cover size-full" aria-label="User avatar">

        <!-- Fallback Initials -->
        @if ($fallback)
            <span x-show="!isShowingImage() && !isLoading"
                class="{{ $textSize }} text-gray-700 font-semibold dark:text-white" aria-label="User initials">
                {{ $fallback }}
            </span>
        @endif

        <!-- Default Icon Fallback -->
        @if (!$fallback)
            <x-icon-user-circle x-show="!isShowingImage() && !isLoading"
                class="w-full h-full text-gray-300 dark:text-gray-500" />
        @endif
    </div>

    <!-- Edit Button (Conditional) -->
    @if ($editable)
        <x-forms.button class="absolute bottom-0 right-0" icon="icon-upload" @click="selectFile()">
        </x-forms.button>

        <x-forms.button class="absolute bottom-0 left-0" icon="icon-restore" btnBg="bg-red-400 dark:bg-red-600"
            btnHover="hover:bg-red-500" x-show="hasPreview" @click="reset()">
        </x-forms.button>

        <input type="file" name="{{ $name }}" accept="image/*" x-ref="fileInput" @change="updatePreview"
            class="hidden">
    @endif
</div>

<script>
    function avatarComponent(initialUrl) {
        return {
            originalUrl: initialUrl || null,
            previewUrl: initialUrl || null,
            hasPreview: false,
            isLoading: false,
            error: null,

            selectFile() {
                this.$refs.fileInput.click();
            },

            updatePreview(event) {
                const file = event.target.files[0];
                if (!file) return;

                const maxSize = 2 * 1024 * 1024; // 2MB
                const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

                if (!allowedTypes.includes(file.type)) {
                    this.error = 'Only JPG, PNG, or WebP files allowed.';
                    return;
                }

                if (file.size > maxSize) {
                    this.error = 'Image must be less than 2MB.';
                    return;
                }

                this.error = null;
                this.isLoading = true;

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewUrl = e.target.result;
                    this.hasPreview = true;
                    this.isLoading = false;
                };
                reader.readAsDataURL(file);
            },

            reset() {
                this.previewUrl = this.originalUrl;
                this.$refs.fileInput.value = null;
                this.hasPreview = false;
                this.error = null;
            },

            isShowingImage() {
                return this.previewUrl !== null && this.previewUrl !== '';
            }
        }
    }
</script>
