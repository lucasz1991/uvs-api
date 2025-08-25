<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use Livewire\Attributes\Session;



class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = null;
    public $selectedTag = null;
    public $selectedCategoryObj;
    public $minPrice = 0;
    public $maxPrice = 1500;
    public $initialMinPrice = 0;
    public $initialMaxPrice = 1500;
    public $ageGroup = null;
    public $sortField = 'created_at'; 
    public $sortDirection = 'desc';
    public $productIsList = 'false';
    public $category = null;

    public $loadedPages = 1;

    public $productId;

    protected $listeners = [
        'priceRangeUpdated',
        'redirectLoginWishlist'
    ];

    protected $queryString = [
        'search' => ['except' => null],
        'selectedCategory' => ['except' => null],
        'selectedTag' => ['except' => null],
        'ageGroup' => ['except' => null],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'minPrice' => ['except' => null],
        'maxPrice' => ['except' => null],
        'loadedPages' => ['except' => null],
    ];

    public function updated($property)
    {
        // Resette Pagination bei Änderungen an den Filtern
        if (in_array($property, ['search', 'selectedCategory', 'selectedTag', 'ageGroup', 'sortField', 'sortDirection', 'minPrice', 'maxPrice'])) {
            $this->resetPage();
        }
    }


    public function toggleLayout()
    {
        $this->productIsList = !$this->productIsList;
        session()->put('product_list', $this->productIsList);
    }

    public function sort($criteria)
    {
        switch ($criteria) {
            case 'popular_asc':
                $this->sortField = 'views'; // Beispielspalte für Beliebtheit
                $this->sortDirection = 'asc';
                break;
            case 'popular_desc':
                $this->sortField = 'views';
                $this->sortDirection = 'desc';
                break;
            case 'price_asc':
                $this->sortField = 'price';
                $this->sortDirection = 'asc';
                break;
            case 'price_desc':
                $this->sortField = 'price';
                $this->sortDirection = 'desc';
                break;
            case 'newest_first':
                $this->sortField = 'created_at'; // Beispiel für neue Produkte
                $this->sortDirection = 'desc';
                break;
            default:
                $this->sortField = 'created_at'; // Fallback zu Standard
                $this->sortDirection = 'desc';
                break;
        }
        $this->resetPage(); // Zurücksetzen der Pagination
    }

    public function performSearch()
    {
        $this->resetPage();
    }


    public function loadMore()
    {
        $this->loadedPages++;
    }


    public function updating($field)
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilter($filter, $value = null)
    {
        if ($filter === 'category') {
            $this->selectedCategory = null;
        } elseif ($filter === 'tag') {
            $this->selectedTag = null;
        } elseif ($filter === 'price') {
            $this->minPrice = $this->initialMinPrice;
            $this->maxPrice = $this->initialMaxPrice;
            $this->dispatch('resetPriceRange', $this->initialMinPrice, $this->initialMaxPrice);
        } elseif ($filter === 'search') {
            $this->search = '';
        } elseif ($filter === 'ageGroup') {
            $this->ageGroup = null;
        }
    
        $this->resetPage(); 
    }


    public function redirectLoginWishlist()
    {
        session()->flash('message', 'Bitte melde dich an, um dieses Produkt zu deiner Wunschliste hinzuzufügen. Wenn du noch kein Konto hast, kannst du dich registrieren und alle Funktionen unserer Seite nutzen.');
        session()->flash('messageType', 'warning');
        $this->redirect('/login', navigate: true);
    }

    public function toggleLikedProduct($productId)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->likedProducts()->where('product_id', $productId)->exists()) {
            // Produkt aus LikedProducts entfernen
            $user->likedProducts()->detach($productId);
        } else {
            // Produkt zu LikedProducts hinzufügen   
            $user->likedProducts()->attach($productId);
        }
        // Event auslösen
        $this->dispatch('likedProductsUpdated');
    }

    public function priceRangeUpdated()
    {
        if ($this->maxPrice < $this->minPrice) {
            $temp = $this->minPrice;
            $this->minPrice = $this->maxPrice;
            $this->maxPrice = $temp;
        }
    }

    protected function getCategoryAndChildrenNames($category)
    {
        $names = [$category->name];

        if ($category->children->isNotEmpty()) {
            foreach ($category->children as $child) {
                $names = array_merge($names, $this->getCategoryAndChildrenNames($child));
            }
        }

        return $names;
    }
    public function render()
    {
        $this->productIsList = session()->get('product_list', false);
        
        $query = Product::query();
        
        $categories = Category::with('children.children')
        ->where(function ($query) {
            $query->whereHas('products', function ($productQuery) {
                $productQuery->where('status', 2);
            })
            ->orWhereHas('children.products', function ($productQuery) {
                $productQuery->where('status', 2);
            })
            ->orWhereHas('children.children.products', function ($productQuery) {
                $productQuery->where('status', 2);
            });
        })
        ->whereNull('parent_id') // Nur Hauptkategorien
        ->get();

        
        
        $tags = Tag::all()->filter(function ($tag) {
            return Product::whereRaw('JSON_CONTAINS(tags, ?)', [json_encode($tag->id)])
                ->where('status', 2)
                ->exists();
        });

        // Filter: Kategorie (inklusive Children)
        if ($this->selectedCategory) {
            $this->selectedCategoryObj = Category::find($this->selectedCategory);
            if ($this->selectedCategoryObj) {
                // Hole die Namen der aktuellen Kategorie und aller Children
                $categoryNames = $this->getCategoryAndChildrenNames($this->selectedCategoryObj);
                $query->whereIn('category', $categoryNames);
            }
        }

        if ( $this->minPrice != $this->initialMinPrice) {
            $query->where('price', '>=', $this->minPrice);
        }
        
        if ( $this->maxPrice != $this->initialMaxPrice) {
            $query->where('price', '<=', $this->maxPrice);
        }

        if ($this->search != "") {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
    
        if (!is_null($this->selectedTag)) {
            // Hole die ID des ausgewählten Tags basierend auf dem Namen
            $tagId = Tag::where('name', $this->selectedTag)->value('id');
        
            if ($tagId) {
                $query->whereRaw('JSON_CONTAINS(tags, ?)', [json_encode((int) $tagId)]);
            }
        }

        // Filter: Nur Produkte mit Status 2
        $query->where('status', 2);
 
        // Sortierung anwenden
        $products = $query->orderBy($this->sortField, $this->sortDirection)
          ->paginate(12 * $this->loadedPages);

        return view('livewire.product-list', [
            'products' => $products,
            'categories' => $categories,
            'tags' => $tags,
        ])->layout('layouts.app');
    }
}

