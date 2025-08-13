<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Toolbar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public bool $search = false,
        public bool $filter = false,
        public bool $create = false,
        public ?string $createRoute = null,
        public bool $delete = false,
        public bool $export = false,
        public array $exportRoutes = [],
        public array $exportItems = [],
    ) {
        $defaultExportItems = [
            [
                'label' => 'Export Excel',
                'icon' => 'icon-file-type-xls',
            ],
            [
                'label' => 'Export CSV',
                'icon' => 'icon-file-type-csv',
            ],
        ];

        // Merge default export items with provided routes
        foreach ($defaultExportItems as $key => $item) {
            if (isset($exportRoutes[$key]['route'])) {
                $this->exportItems[] = array_merge($item, [
                    'route' => $exportRoutes[$key]['route'],
                ]);
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.toolbar');
    }
}
