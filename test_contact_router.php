<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Contact Router</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">🧪 Test du formulaire de contact via le routeur</h1>
        
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">📞 Via le routeur (index.php)</h2>
                <p class="mb-4">Test du formulaire via le système de routage principal.</p>
                <a href="index.php?page=contact" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Tester via routeur
                </a>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">📝 Version simple (contact_simple.php)</h2>
                <p class="mb-4">Version simplifiée qui fonctionne déjà.</p>
                <a href="contact_simple.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Tester version simple
                </a>
            </div>
        </div>
        
        <div class="mt-8 bg-yellow-50 border border-yellow-200 p-4 rounded">
            <h3 class="font-bold text-yellow-800">💡 Instructions :</h3>
            <ol class="list-decimal list-inside text-yellow-700 mt-2">
                <li>Cliquez sur "Tester via routeur"</li>
                <li>Remplissez le formulaire de contact</li>
                <li>Envoyez le message</li>
                <li>Vérifiez dans l'administration si le message apparaît</li>
            </ol>
        </div>
        
        <div class="mt-4">
            <a href="admin.php" class="text-blue-600 hover:underline">👨‍💼 Vérifier l'administration</a> |
            <a href="debug_contact.php" class="text-blue-600 hover:underline">🔍 Diagnostic complet</a> |
            <a href="index.html" class="text-blue-600 hover:underline">🏠 Accueil</a>
        </div>
    </div>
</body>
</html>

