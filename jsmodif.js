// Formulaire de contact avec vraie base de données
async function sendMessage(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    // Afficher loading
    submitBtn.textContent = '⏳ Envoi en cours...';
    submitBtn.disabled = true;
    
    try {
        // Convertir FormData en objet JSON
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        
        console.log('Données envoyées:', data);
        
        const response = await fetch('backend/api/contact.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        console.log('Réponse API:', result);
        
        if (result.success) {
            // Succès
            submitBtn.textContent = '✅ Message envoyé !';
            submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            submitBtn.classList.add('bg-green-600');
            
            // Réinitialiser le formulaire
            form.reset();
            
            // Afficher notification
            showNotification('Message enregistré dans la base de données! ID: ' + result.id, 'success');
            
            setTimeout(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-green-600');
                submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }, 4000);
            
        } else {
            // Erreur
            throw new Error(result.message);
        }
        
    } catch (error) {
        console.error('Erreur complète:', error);
        
        // Afficher message d'erreur détaillé
        let errorMessage = 'Erreur lors de l\'envoi';
        
        if (error.message.includes('Failed to fetch')) {
            errorMessage = '❌ Serveur inaccessible. Vérifiez que XAMPP est démarré (Apache + MySQL)';
        } else if (error.message.includes('Network Error')) {
            errorMessage = '❌ Problème de réseau. Vérifiez votre connexion';
        } else {
            errorMessage = '❌ ' + error.message;
        }
        
        showNotification(errorMessage, 'error');
        submitBtn.textContent = '❌ Erreur - Réessayer';
        
        setTimeout(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }, 3000);
    }
}

// Attacher l'événement au formulaire
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', sendMessage);
    }
});


// Notification améliorée
function showNotification(message, type = 'info') {
    // Supprimer les notifications existantes
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    notification.className = `custom-notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-md ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    } text-white transform translate-x-full transition-transform duration-300`;
    
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                ✕
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 100);
    
    // Auto-suppression après 5 secondes
    setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}