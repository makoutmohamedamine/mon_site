// Upload de fichier
document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('api/fichiers.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if(result.success) {
            alert('Fichier uploadé avec succès!');
            this.reset();
            loadFichiers(); // Recharger la liste
        } else {
            alert('Erreur: ' + result.message);
        }
    } catch(error) {
        console.error('Erreur:', error);
        alert('Erreur réseau');
    }
});

// Charger et afficher les fichiers
async function loadFichiers(module = null, semestre = null) {
    let url = 'api/fichiers.php?';
    
    if(module) url += `module=${module}&`;
    if(semestre) url += `semestre=${semestre}`;
    
    try {
        const response = await fetch(url);
        const data = await response.json();
        
        if(data.fichiers) {
            displayFichiers(data.fichiers);
        }
    } catch(error) {
        console.error('Erreur:', error);
    }
}

// Afficher les fichiers
function displayFichiers(fichiers) {
    const container = document.getElementById('fichiers-container');
    container.innerHTML = '';
    
    fichiers.forEach(fichier => {
        const fichierHTML = `
            <div class="bg-white rounded-lg shadow p-4 flex justify-between items-center">
                <div>
                    <h4 class="font-semibold">${fichier.nom_affichage}</h4>
                    <p class="text-sm text-gray-600">
                        ${fichier.type_fichier} • ${fichier.taille} • ${new Date(fichier.date_upload).toLocaleDateString()}
                    </p>
                </div>
                <a href="download.php?id=${fichier.id}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                   📥 Télécharger
                </a>
            </div>
        `;
        container.innerHTML += fichierHTML;
    });
}