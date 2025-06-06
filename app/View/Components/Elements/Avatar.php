<?php

namespace App\View\Components\Elements;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class Avatar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name = 'avatar',
        public string $size = 'size-12',
        public string $textSize = 'text-sm',
        public string $shape = 'rounded-full',
        public bool $editable = false,
        public $user = null,
        public $avatarUrl = null,
        public $fallback = null,
    ) {
        $this->avatarUrl = $this->getAvatar();
        $this->fallback = $this->getInitials();
    }

    // Resolve avatar URL
    protected function getAvatar()
    {
        if (! $this->user) {
            return null;
        }

        // If avatar is a URL
        if (filter_var($this->user?->profile->avatar, FILTER_VALIDATE_URL)) {
            return $this->user->profile->avatar;
        }

        // If avatar is a file path (storage)
        if ($this->user?->profile->avatar && Storage::disk('public')->exists($this->user->profile->avatar)) {
            return Storage::url($this->user->profile->avatar);
        }

        return null; // No avatar found
    }

    // Generate initials (e.g., "John Doe" → "JD")
    protected function getInitials()
    {
        if (! $this->user || ! $this->user?->full_name) {
            return null;
        }

        $initials = '';
        $names = preg_split('/\s+/', trim($this->user->full_name));

        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
            if (mb_strlen($initials) >= 2) {
                break;
            }
        }

        if (mb_strlen($initials) < 2 && count($names) === 1) {
            $initials .= strtoupper(mb_substr($names[0], 1, 1));
        }

        return $initials;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.elements.avatar');
    }
}
