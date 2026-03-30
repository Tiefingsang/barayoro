<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Afficher la liste des produits
     */
    public function index(Request $request)
    {
        $query = Product::where('company_id', Auth::user()->company_id);

        // Filtrer par recherche
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // Filtrer par catégorie
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filtrer par statut
        if ($request->has('status') && $request->status) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filtrer par type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Trier
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        $products = $query->paginate($request->get('per_page', 15));

        // Récupérer les catégories uniques pour le filtre
        $categories = Product::where('company_id', Auth::user()->company_id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Enregistrer un nouveau produit
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'type' => 'required|in:product,service',
            'unit' => 'nullable|string|max:50',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'stock_quantity' => 'nullable|integer|min:0',
            'min_stock_quantity' => 'nullable|integer|min:0',
            'max_stock_quantity' => 'nullable|integer|min:0',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'barcode' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'is_taxable' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Générer le code produit
        $code = 'PROD-' . strtoupper(Str::random(8));
        while (Product::where('code', $code)->exists()) {
            $code = 'PROD-' . strtoupper(Str::random(8));
        }

        $product = Product::create([
            'uuid' => Str::uuid(),
            'company_id' => Auth::user()->company_id,
            'code' => $code,
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'brand' => $request->brand,
            'type' => $request->type,
            'unit' => $request->unit ?? 'piece',
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'tax_rate' => $request->tax_rate ?? 0,
            'stock_quantity' => $request->stock_quantity ?? 0,
            'min_stock_quantity' => $request->min_stock_quantity ?? 0,
            'max_stock_quantity' => $request->max_stock_quantity,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'is_active' => $request->has('is_active'),
            'is_taxable' => $request->has('is_taxable'),
        ]);

        // Gérer les images
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = [
                    'path' => $path,
                    'url' => Storage::url($path),
                    'name' => $image->getClientOriginalName(),
                ];
            }
            $product->update(['images' => $images]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Afficher les détails d'un produit
     */
    public function show(Product $product)
    {
        $this->checkCompanyAccess($product);

        return view('products.show', compact('product'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Product $product)
    {
        $this->checkCompanyAccess($product);

        return view('products.edit', compact('product'));
    }

    /**
     * Mettre à jour un produit
     */
    public function update(Request $request, Product $product)
    {
        $this->checkCompanyAccess($product);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'type' => 'required|in:product,service',
            'unit' => 'nullable|string|max:50',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'stock_quantity' => 'nullable|integer|min:0',
            'min_stock_quantity' => 'nullable|integer|min:0',
            'max_stock_quantity' => 'nullable|integer|min:0',
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'is_taxable' => 'boolean',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'brand' => $request->brand,
            'type' => $request->type,
            'unit' => $request->unit ?? 'piece',
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'tax_rate' => $request->tax_rate ?? 0,
            'stock_quantity' => $request->stock_quantity ?? 0,
            'min_stock_quantity' => $request->min_stock_quantity ?? 0,
            'max_stock_quantity' => $request->max_stock_quantity,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'is_active' => $request->has('is_active'),
            'is_taxable' => $request->has('is_taxable'),
        ]);

        // Gérer les nouvelles images
        if ($request->hasFile('images')) {
            $images = $product->images ?? [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = [
                    'path' => $path,
                    'url' => Storage::url($path),
                    'name' => $image->getClientOriginalName(),
                ];
            }
            $product->update(['images' => $images]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Supprimer un produit (soft delete)
     */
    public function destroy(Product $product)
    {
        $this->checkCompanyAccess($product);

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produit supprimé avec succès.');
    }

    /**
     * Restaurer un produit supprimé
     */
    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $this->checkCompanyAccess($product);

        $product->restore();

        return redirect()->route('products.index')
            ->with('success', 'Produit restauré avec succès.');
    }

    /**
     * Supprimer définitivement un produit
     */
    public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $this->checkCompanyAccess($product);

        // Supprimer les images associées
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image['path']);
            }
        }

        $product->forceDelete();

        return redirect()->route('products.index')
            ->with('success', 'Produit supprimé définitivement.');
    }

    /**
     * Mettre à jour le stock
     */
    public function updateStock(Request $request, Product $product)
    {
        $this->checkCompanyAccess($product);

        $request->validate([
            'quantity' => 'required|integer',
            'operation' => 'required|in:add,remove',
            'reason' => 'nullable|string',
        ]);

        $oldStock = $product->stock_quantity;

        if ($request->operation === 'add') {
            $product->stock_quantity += $request->quantity;
        } else {
            $product->stock_quantity -= $request->quantity;
        }

        $product->save();

        // Enregistrer l'historique du stock (optionnel)
        // StockHistory::create([...]);

        return redirect()->route('products.show', $product)
            ->with('success', "Stock mis à jour : {$oldStock} → {$product->stock_quantity}");
    }

    /**
     * Exporter les produits en CSV
     */
    public function export()
    {
        $products = Product::where('company_id', Auth::user()->company_id)->get();

        $filename = 'produits_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');

        // En-têtes CSV
        fputcsv($handle, ['Code', 'Nom', 'Catégorie', 'Type', 'Prix d\'achat', 'Prix de vente', 'Stock', 'Statut']);

        // Données
        foreach ($products as $product) {
            fputcsv($handle, [
                $product->code,
                $product->name,
                $product->category,
                $product->type,
                $product->purchase_price,
                $product->selling_price,
                $product->stock_quantity,
                $product->is_active ? 'Actif' : 'Inactif',
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Vérifier que le produit appartient à l'entreprise
     */
    private function checkCompanyAccess(Product $product)
    {
        if ($product->company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
