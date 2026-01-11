<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Translation;
use App\Models\Language;
use App\Services\TranslationService;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Pagination\LengthAwarePaginator;
class TranslationController extends Controller
{
    protected $translationService;
    
    
    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
         // Ensure helpers are loaded (only if composer autoload didn't work)
        if (!function_exists('available_languages')) {
            require_once app_path('Helpers/TranslationHelpers.php');
        }
    }
    
    // public function index(Request $request)
    // {
       

    //    $translations = Translation::when($request->search, function ($q) use ($request) {
    //     $q->where('key', 'like', '%'.$request->search.'%')
    //       ->orWhere('value', 'like', '%'.$request->search.'%');
    // })->get();

    // if ($request->ajax()) {
    //     return response()->json([
    //         'html' => view('admin.translations.partials.rows', compact('translations'))->render()
    //     ]);
    // }

    // return view('admin.dashboard.translations.index', [
    //     'translations' => new LengthAwarePaginator([], 0, 50),
    //     'search' => null,
    //     'languages' => available_languages()
    // ]);


    // }


    // AJAX method to get translations with pagination and search
    public function index(Request $request)
{
    $translations = Translation::when($request->search, function ($q) use ($request) {
        $q->where('key', 'like', '%'.$request->search.'%')
          ->orWhere('value', 'like', '%'.$request->search.'%');
    })->orderBy('id','desc')->get();

    if ($request->ajax()) {
        return response()->json([
            'html' => view(
                'admin.dashboard.translations.partials.rows',
                compact('translations')
            )->render()
        ]);
    }

    return view('admin.dashboard.translations.index', compact('translations'));
}
public function ajaxTranslations(Request $request)
{
    $query = Translation::with('values');

    if($request->has('search') && $request->search != ''){
        $query->where('key', 'like', '%'.$request->search.'%');
    }

    $translations = $query->orderBy('id','desc')->paginate(10);

    $data = $translations->map(function($t){
        return [
            'id' => $t->id,
            'key' => $t->key,
            'group' => $t->group,
            'values' => $t->values->pluck('value','lang_code')->toArray() // assuming values relation
        ];
    });

    return response()->json([
        'translations' => $data,
        'pagination' => [
            'current_page' => $translations->currentPage(),
            'last_page' => $translations->lastPage(),
        ]
    ]);
}
public function create(Request $request){
    $request->validate([
        'key'=>'required|string|max:255',
        'translations'=>'required|array',
    ]);

    $translation = Translation::create([
        'key'=>$request->key,
        'group'=>$request->group ?? null
    ]);

    foreach($request->translations as $lang=>$val){
        $translation->values()->create([
            'lang_code'=>$lang,
            'value'=>$val
        ]);
    }

    return response()->json([
        'success'=>true,
        'translation'=>[
            'id'=>$translation->id,
            'key'=>$translation->key,
            'group'=>$translation->group,
            'values'=>$translation->values->pluck('value','lang_code')->toArray()
        ]
    ]);
}

    protected function getTranslationsAjax(Request $request)
    {
        $search = $request->get('search');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 50);

        $query = Translation::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('key', 'like', '%' . $search . '%')
                  ->orWhere('group', 'like', '%' . $search . '%')
                  ->orWhere('text', 'like', '%' . $search . '%');
            });
        }

        $translations = $query->paginate($perPage, ['*'], 'page', $page);

        // Format data for AJAX response
        $data = $translations->map(function($translation) {
            $translationData = [
                'id' => $translation->id,
                'key' => $translation->key,
                'group' => $translation->group,
                'text' => $translation->text,
                'values' => []
            ];

            // Get translation values for each language
            $languages = available_languages();
            foreach ($languages as $lang) {
                $translationData['values'][$lang->code] = $translation->getValue($lang->code);
            }

            return $translationData;
        });

        return response()->json([
            'translations' => $data,
            'pagination' => [
                'current_page' => $translations->currentPage(),
                'last_page' => $translations->lastPage(),
                'per_page' => $translations->perPage(),
                'total' => $translations->total(),
                'from' => $translations->firstItem(),
                'to' => $translations->lastItem(),
                'has_more_pages' => $translations->hasMorePages(),
                'next_page_url' => $translations->nextPageUrl(),
                'prev_page_url' => $translations->previousPageUrl(),
            ],
            'search' => $search
        ]);
    }
    
   public function update(Request $request)
        {
            try {
                $validated = $request->validate([
                    'translation_id' => 'required|exists:translations,id',
                    'translations' => 'required|array'
                ]);

                $translation = Translation::find($validated['translation_id']);

                foreach ($validated['translations'] as $lang => $value) {
                    $this->translationService->set(
                        $translation->key,
                        $value,
                        $translation->group,
                        $lang
                    );
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Translation saved successfully'
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Return validation errors as JSON
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            } catch (\Exception $e) {
                // Any other exception
                \Log::error('Translation update error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Server error'
                ], 500);
            }
        }

    
    public function autoTranslate(Request $request)
    {
        $validated = $request->validate([
            'translation_id' => 'required|exists:translations,id',
            'source_text' => 'required|string'
        ]);
        
        $translation = Translation::find($validated['translation_id']);
        $sourceText = $validated['source_text'];
        
        $languages = Language::where('is_active', true)
            ->where('code', '!=', 'en')
            ->get();
            
        $results = [];
        
        foreach ($languages as $language) {
            try {
                // Using Google Translate (install package: composer require stichoza/google-translate-php)
                $translator = new GoogleTranslate();
                $translator->setSource('en');
                $translator->setTarget($language->code);
                
                $translatedText = $translator->translate($sourceText);
                
                // Save to database
                $this->translationService->set(
                    $translation->key,
                    $translatedText,
                    $translation->group,
                    $language->code
                );
                
                $results[$language->code] = $translatedText;
                
            } catch (\Exception $e) {
                $results[$language->code] = null;
            }
        }
        
        return response()->json([
            'success' => true,
            'translations' => $results
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:255',
            'group' => 'required|string|max:255',
        ]);

        $key = $request->key;
        $group = $request->group;

        // Check if already exists
        $existing = Translation::where('key', $key)->where('group', $group)->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Translation key already exists for this group.'
            ], 400);
        }

        // Create for all active languages
        $languages = Language::where('is_active', true)->get();
        foreach ($languages as $lang) {
            Translation::create([
                'key' => $key,
                'group' => $group,
                'locale' => $lang->code,
                'text' => $key, // Default to key, can be edited later
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Translation added successfully!'
        ]);
    }
}