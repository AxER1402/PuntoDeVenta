<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = User::all();
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('clientes.index')
                        ->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cliente = User::findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cliente = User::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cliente = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($cliente->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $cliente->update($updateData);

        return redirect()->route('clientes.index')
                        ->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cliente = User::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')
                        ->with('success', 'Cliente eliminado exitosamente.');
    }

    /**
     * Search clients for autocomplete with similarity matching
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q');
            
            if (strlen($query) < 2) {
                return response()->json([]);
            }

            // Búsqueda principal: coincidencias exactas o que contengan el texto
            $exactMatches = User::where('name', 'like', "%{$query}%")
                               ->orWhere('email', 'like', "%{$query}%")
                               ->limit(10)
                               ->get(['id', 'name', 'email']);

            // Si hay coincidencias exactas, las devolvemos
            if ($exactMatches->count() > 0) {
                return response()->json($exactMatches);
            }

            // Si no hay coincidencias exactas, buscamos usuarios parecidos
            $similarUsers = $this->findSimilarUsers($query);
            
            return response()->json($similarUsers);
        } catch (\Exception $e) {
            \Log::error('Error en búsqueda de clientes: ' . $e->getMessage());
            return response()->json(['error' => 'Error al buscar clientes'], 500);
        }
    }

    /**
     * Find users with similar names or emails
     */
    private function findSimilarUsers($query)
    {
        try {
            $query = strtolower(trim($query));
            $words = array_filter(explode(' ', $query), function($word) {
                return strlen($word) >= 2;
            });
            
            if (empty($words)) {
                return collect();
            }
            
            $allUsers = User::all(['id', 'name', 'email']);
            $similarUsers = collect();

            foreach ($allUsers as $user) {
                $name = strtolower($user->name);
                $email = strtolower($user->email);
                $similarity = 0;

                // Verificar similitud en el nombre
                foreach ($words as $word) {
                    // Buscar si alguna palabra del nombre contiene esta palabra
                    $nameWords = explode(' ', $name);
                    foreach ($nameWords as $nameWord) {
                        if (strpos($nameWord, $word) !== false) {
                            $similarity += 3; // Peso alto para coincidencias en nombre
                        }
                    }

                    // Buscar en email
                    if (strpos($email, $word) !== false) {
                        $similarity += 2; // Peso medio para coincidencias en email
                    }
                }

                // Solo incluir si hay cierta similitud
                if ($similarity > 0) {
                    $user->similarity_score = $similarity;
                    $similarUsers->push($user);
                }
            }

            // Ordenar por score de similitud y tomar los mejores
            return $similarUsers->sortByDesc('similarity_score')->take(10)->values();
        } catch (\Exception $e) {
            \Log::error('Error en findSimilarUsers: ' . $e->getMessage());
            return collect();
        }
    }
}
