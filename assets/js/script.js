// Fonction pour afficher les notifications
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}

// Gestion des formulaires
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'En cours...';
        
        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: form.method,
                body: form.enctype === 'multipart/form-data' ? formData : new URLSearchParams(formData),
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                showNotification(result.message || 'Opération réussie');
                if (result.redirect) {
                    setTimeout(() => window.location.href = result.redirect, 1500);
                }
            } else {
                showNotification(result.message || 'Une erreur est survenue', 'error');
            }
        } catch (error) {
            showNotification('Erreur de connexion', 'error');
            console.error(error);
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
});

// Initialisation du calendrier (si présent sur la page)
if (document.getElementById('calendar')) {
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: '/rendezvous/get_events.php',
            eventClick: function(info) {
                alert('Rendez-vous: ' + info.event.title);
            }
        });
        calendar.render();
    });
}

// Gestion des suppressions
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
            e.preventDefault();
        }
    });
});


function toggleMenu() {
    const menu = document.querySelector('nav ul');
    const toggleButton = document.querySelector('.menu-toggle');
    menu.classList.toggle('open');
    toggleButton.classList.toggle('open');
}


  const items = document.querySelectorAll('.animate-on-scroll');

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1
  });

  items.forEach(item => {
    observer.observe(item);
  });


