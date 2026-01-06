use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        if (!$q) {
            return response()->json([]);
        }

        $products = Product::where('name', 'like', "%$q%")
            ->limit(5)
            ->get(['id', 'name']);

        $categories = Category::where('name', 'like', "%$q%")
            ->limit(5)
            ->get(['id', 'name']);

        $pages = collect([
            ['title' => 'FAQ', 'url' => route('faq')],
            ['title' => 'Tentang Kami', 'url' => route('about')],
            ['title' => 'Syarat & Ketentuan', 'url' => route('terms')],
            ['title' => 'Hubungi Kami', 'url' => route('contact')],
        ])->filter(fn($page) =>
            str_contains(strtolower($page['title']), strtolower($q))
        );

        return response()->json([
            'products' => $products,
            'categories' => $categories,
            'pages' => $pages->values(),
        ]);
    }
}
