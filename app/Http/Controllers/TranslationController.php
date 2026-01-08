<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Translation;
use App\Models\Language;
use App\Services\TranslationService;
use Stichoza\GoogleTranslate\GoogleTranslate;

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
    
    public function index(Request $request)
    {
        // dd(available_languages());

        $group = $request->get('group', 'frontend');
        $translations = Translation::where('group', $group)->paginate(50);
        
        return view('admin.dashboard.translations.index', compact('translations'));
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
}